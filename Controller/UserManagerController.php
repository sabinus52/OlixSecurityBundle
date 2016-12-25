<?php
/**
 * Controller de la gestion des utilisateurs
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
use Olix\SecurityBundle\Form\Type\UserCreateFormType;
use Olix\SecurityBundle\Form\Type\UserEditFormType;
use Olix\SecurityBundle\Form\Type\UserChangePwdFormType;


class UserManagerController extends Controller
{

    /**
     * Page de listing des utilisateurs
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction ()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }
        
        // Création de la Datatables
        $datatable = $this->get('olix_security.datatable.user');
        $datatable->buildDatatable(array(
            'entity' => $this->container->getParameter('fos_user.model.user.class'),
            'delay'  => $this->container->getParameter('olix.security.activity.delay'),
        ));
        
        // Déclaration d'un formulaire vide pour la suppression d'un élément pour éviter le piratage
        $form = $this->createFormBuilder()->getForm();
        
        // Affichage de la page
        return $this->container->get('olix.admin')->render('OlixSecurityBundle:UserManager:list.html.twig', 'olix_security_users', array(
            'form'         => $form->createView(),
            'datatable'    => $datatable,
        ));
    }


    /**
     * Retourne les utilisateurs en mode AJAX
     *
     * @return \Symfony\Component\HttpFoundation\Response : JSON
     */
    public function getResultsAction()
    {
        $datatable = $this->get('olix_security.datatable.user');
        $datatable->buildDatatable(array(
            'entity' => $this->container->getParameter('fos_user.model.user.class'),
            'delay'  => $this->container->getParameter('olix.security.activity.delay'),
        ));
        
        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);
        
        return $query->getResponse();
    }


    /**
     * Page du formulaire de saisie d'un nouvel utilisateur
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction ()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }
        
        $manager = $this->container->get('fos_user.user_manager');
        
        // Création du formulaire
        $user = $manager->createUser();
        $form = $this->createForm(
            new UserCreateFormType($manager->getClass()),
            $user
        );
        
        // Validation du formulaire
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                
                $manager->updateUser($user);
                
                $this->get('session')->getFlashBag()->set('success', "L'utilisateur <strong>".$user->getUsername()."</strong> a été ajouté avec succès");
                return $this->redirect($this->generateUrl('olix_security_manager_user_list'));
            }
            $form->addError(new FormError('Tous les champs ne sont pas complètement remplis'));
        }
        
        // Affichage du formulaire
        return $this->container->get('olix.admin')->render('OlixSecurityBundle:UserManager:create.html.twig', 'olix_security_users', array(
            'form'   => $form->createView(),
        ));
    }


    /**
     * Page du formulaire de modification d'un utilisateur
     *
     * @param string $username Login de l'utilisateur
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction ($username)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }
        
        $manager = $this->container->get('fos_user.user_manager');
        $user = $manager->findUserByUsername($username);
        
        // Création du formulaire
        $form = $this->createForm(
            new UserEditFormType($manager->getClass()),
            $user
        );
        $form->get('expiresAt')->setData($user->getExpiresAt());
        
        // Validation du formulaire
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                // Initialise l'expiration
                $expires = $form->get('expiresAt')->getData();
                if ($expires)
                    $user->setExpiresAt($form->get('expiresAt')->getData());
                else
                    $user->setExpiresAt(new \DateTime('2099-12-31'));
                
                $manager->updateUser($user);
                
                $this->get('session')->getFlashBag()->set('success', "L'utilisateur <strong>".$user->getUsername()."</strong> a été modifié avec succès");
                return $this->redirect($this->generateUrl('olix_security_manager_user_list'));
            }
            $form->addError(new FormError('Tous les champs ne sont pas complètement remplis'));
        }
        
        // Affichage du formulaire
        return $this->container->get('olix.admin')->render('OlixSecurityBundle:UserManager:edit.html.twig', 'olix_security_users', array(
            'user'   => $user,
            'form'   => $form->createView(),
        ));
    }


    /**
     * Page du formulaire de suppression d'un utilisateur
     *
     * @param string $username Login de l'utilisateur
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction ($username)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }
        
        // Récupération de l'objet User
        $manager = $this->container->get('fos_user.user_manager');
        $user = $manager->findUserByUsername($username);
        
        // Création du formulaire
        $form = $this->createFormBuilder()->getForm();
        
        // Validation du formulaire
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                
                $manager->deleteUser($user);
                
                $this->get('session')->getFlashBag()->set('success', "L'utilisateur <strong>".$user->getUsername()."</strong> a été supprimé avec succès");
            }
        }
        
        return $this->redirect($this->generateUrl('olix_security_manager_user_list'));
    }


    /**
     * Page du formulaire de reinitialisation de mot de passe d'un utilisateur
     *
     * @param string $username Login de l'utilisateur
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changePasswordAction ($username)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }
        
        // Récupération de l'objet User
        $manager = $this->container->get('fos_user.user_manager');
        $user = $manager->findUserByUsername($username);
        
        // Création du formulaire
        $form = $this->createForm(
            new UserChangePwdFormType($manager->getClass()),
            $user
        );
        
        // Validation du formulaire
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                
                $manager->updateUser($user);
                
                $this->get('session')->getFlashBag()->set('success', "Le mot de passe de <strong>".$user->getUsername()."</strong> a été initialisé avec succès");
                return $this->redirect($this->generateUrl('olix_security_manager_user_list'));
            }
            $form->addError(new FormError('Tous les champs ne sont pas complètement remplis'));
        }
        
        // Affichage du formulaire
        return $this->container->get('olix.admin')->render('OlixSecurityBundle:UserManager:changepwd.html.twig', 'olix_security_users', array(
            'user'   => $user,
            'form'   => $form->createView(),
        ));
    }

}