<?php
/**
 * Controller des pages de la sécurité de base (authentification)
 * 
 * @author Olivier <sabinus52@gmail.com>
 * 
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;


class SecurityController extends BaseController
{

    /**
     * Rendu du template de connexion
     * 
     * @param array $data : Données pour le template
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        return $this->container->get('olix.admin')->render('FOSUserBundle:Security:login.html.twig', null, $data);
    }


}