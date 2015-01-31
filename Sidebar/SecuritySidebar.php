<?php
/**
 * Sidebar pour la  gestion des utilisateurs et groupes
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Sidebar;

use Olix\AdminBundle\Factory\SidebarItem;
use Symfony\Component\Security\Core\SecurityContext;


class SecuritySidebar
{

    /**
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    protected $context;


    /**
     * Constructeur
     * 
     * @param SecurityContext $context
     */
    public function __construct(SecurityContext $context)
    {
        $this->context = $context;
    }


    /**
     * Construit le menu supplémentaire de la gestion des utilisateurs
     * 
     * @param SidebarItem $sidebar Sidebar d'origine à completer
     */
    public function build(SidebarItem $sidebar)
    {
        if (!$this->context->isGranted('ROLE_SUPER_ADMIN')) return;
        
        $sidebar->addChild('olix_security', array(
            'label' => 'Droits & accès',
            'icon'  => 'fa fa-lock fa-fw',
        ));
    }

}