<?php
/**
 * Controller des pages du profil de l'utilisateur
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Finder\Finder;
use FOS\UserBundle\Model\UserInterface;
use Olix\SecurityBundle\Form\Type\ProfileFormType;
use Olix\SecurityBundle\Avatar;
use Olix\SecurityBundle\Avatar\Gravatar;


class ProfileController extends Controller
{

    /**
     * Modification du profil
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        
        $form = $this->createForm(
            new ProfileFormType($this->container->getParameter('fos_user.model.user.class')),
            $user
        );
        
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);
            $this->container->get('session')->getFlashBag()->set('success', $this->get('translator')->trans('profile.flash.updated', array(), 'FOSUserBundle'));
            return $this->redirect($this->generateUrl('olix_security_profile_edit'));
        }
        
        return $this->container->get('olix.admin')->render('OlixSecurityBundle:Profile:edit.html.twig', null, array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }


    /**
     * Affichage de la liste des avatars disponibles dans un popup
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function avatarAction ()
    {
        $finder = new Finder();
        $result = array();
        $finder->files()->in(__DIR__.'/../Resources/public/avatar')->name('*.png');
        foreach ($finder as $files) {
            $result[$files->getRelativePath()][] = $files->getRelativePathname();
        }
        $user = $this->getUser();
        $gravatar = new Gravatar();
        $gravatar->setAvatarSize(150);
        return $this->render('OlixSecurityBundle:Profile:avatar.html.twig', array(
            'avatars' => $result,
            'gravatar' => $gravatar->get($user->getEmail()),
        ));
    }

}