<?php
/**
 * Controller de la gestion des groupes
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Olix\SecurityBundle\Form\Type\GroupFormType;


class GroupManagerController extends Controller
{

    /**
     * Page de listing des groupes
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction ()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }
        
        // Liste de tous les groupes
        $manager = $this->container->get('fos_user.group_manager');
        $result = $manager->findGroups();
        
        // Création de la Datatables
        $datatable = $this->get('olix_security.datatable.group');
        $datatable->buildDatatable();
        $serializer = $this->get('sg_datatables.serializer');
        $datatable->setData($serializer->serialize($result, 'json'));
        
        // Déclaration d'un formulaire vide pour la suppression d'un élément pour éviter le piratage
        $form = $this->createFormBuilder()->getForm();
        
        // Affichage de la page
        return $this->container->get('olix.admin')->render('OlixSecurityBundle:GroupManager:list.html.twig', 'olix_security_groups', array(
            'form'         => $form->createView(),
            'datatable'    => $datatable,
        ));
    }


    /**
     * Page du formulaire de saisie d'un nouveau groupe
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction ()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }
        
        $manager = $this->container->get('fos_user.group_manager');
        
        // Création du formulaire
        $group = $manager->createGroup('');
        $form = $this->createForm(
            new GroupFormType($manager->getClass(), $this->container->getParameter('security.role_hierarchy.roles')),
            $group
        );
        
        // Validation du formulaire
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                
                $manager->updateGroup($group);
                
                $manager = $this->container->get('fos_user.user_manager');
                // Rajoute les utilisateurs selectionnés dans le groupe
                foreach ($form->get('users')->getData() as $user) {
                    $user->addGroup($group);
                    $manager->updateUser($user);
                }
                
                $this->get('session')->getFlashBag()->set('success', "Le groupe <strong>".$group->getName()."</strong> a été ajouté avec succès");
                return $this->redirect($this->generateUrl('olix_security_manager_group_list'));
            }
            $form->addError(new FormError('Tous les champs ne sont pas complètement remplis'));
        }
        
        // Affichage du formulaire
        return $this->container->get('olix.admin')->render('OlixSecurityBundle:GroupManager:create.html.twig', 'olix_security_groups', array(
            'form'   => $form->createView(),
        ));
    }


    /**
     * Page du formulaire de modification d'un groupe
     *
     * @param integer $id Identifiant du groupe
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction ($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }
        
        $manager = $this->container->get('fos_user.group_manager');
        $group = $manager->findGroupBy(array('id' => $id));
        
        // Création du formulaire
        $form = $this->createForm(
            new GroupFormType($manager->getClass(), $this->container->getParameter('security.role_hierarchy.roles')),
            $group
        );
        
        // Récupération des utilisateurs appartenant à ce groupe (@ignore par FOS) et affecte dans le formulaire
        $repository = $this->getDoctrine()->getRepository('OlixSecurityBundle:User');
        $usersInGroup = $repository->findByGroup($group);
        $form->get('users')->setData($usersInGroup);
        
        // Validation du formulaire
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                
                $manager->updateGroup($group);
                
                $manager = $this->container->get('fos_user.user_manager');
                // Enlève tous les utilisateurs courants actuel du groupe
                foreach ($usersInGroup as $user) {
                    $user->removeGroup($group);
                    $manager->updateUser($user);
                }
                // Rajoute les utilisateurs selectionnés dans le groupe
                foreach ($form->get('users')->getData() as $user) {
                    $user->addGroup($group);
                    $manager->updateUser($user);
                }
                
                $this->get('session')->getFlashBag()->set('success', "Le groupe <strong>".$group->getName()."</strong> a été modifié avec succès");
                return $this->redirect($this->generateUrl('olix_security_manager_group_list'));
            }
            $form->addError(new FormError('Tous les champs ne sont pas complètement remplis'));
        }
        
        // Affichage du formulaire
        return $this->container->get('olix.admin')->render('OlixSecurityBundle:GroupManager:edit.html.twig', 'olix_security_groups', array(
            'group'  => $group,
            'form'   => $form->createView(),
        ));
    }


    /**
     * Page du formulaire de suppression d'un groupe
     *
     * @param integer $id Identifiant du groupe
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction ($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }
        
        // Récupération de l'objet Group
        $manager = $this->container->get('fos_user.group_manager');
        $group = $manager->findGroupBy(array('id' => $id));
        
        // Création du formulaire
        $form = $this->createFormBuilder()->getForm();
        
        // Validation du formulaire
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                
                $manager->deleteGroup($group);
                
                $this->get('session')->getFlashBag()->set('success', "Le groupe <strong>".$group->getName()."</strong> a été supprimé avec succès");
            }
        }
        
        return $this->redirect($this->generateUrl('olix_security_manager_group_list'));
    }

}