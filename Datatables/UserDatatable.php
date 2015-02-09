<?php
/**
 * DataTables de la gestion des utilisateurs
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Datatables;

use Olix\DatatablesBootstrapBundle\Datatable\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;


class UserDatatable extends AbstractDatatableView
{

    public function buildDatatableView()
    {
        $this->getFeatures()
            ->setServerSide(false);
        
        // Déclarations des colonnes
        $this->getColumnBuilder()
            ->add('id', 'column', array(
                  'title' => 'Id',
                  'class' => 'text-center',
            ))
            ->add('avatar', 'column', array(
                  'title' => 'Avatar',
                  'class' => 'text-center',
                  'render' => 'render_column_avatar'
            ))
            ->add('online', 'column', array(
                  'title' => 'Connecté',
                  'class' => 'text-center',
                  'render' => 'render_column_online'
            ))
            ->add('name', 'column', array(
                  'title' => 'Nom'
            ))
            ->add('username', 'column', array(
                  'title' => 'Login'
            ))
            ->add('locked', 'column', array(
                  'title' => 'Statut',
                  'class' => 'text-center',
                  'render' => 'render_column_statut'
            ))
            ->add('expired', 'column', array(
                  'visible' => false,
            ))
            ->add('lastLogin', 'datetime', array(
                  'title' => 'Dernière connexion',
                  'format' => 'DD/MM/YYYY HH:mm',
                  'render' => 'render_column_login'
            ))
            ->add('intervalLastLogin', 'column', array(
                  'visible' => false
            ))
            // Boutons Actions
            ->add(null, 'action', array(
                'title' => '',
                'start_html' => '<div class="olix-actions">',
                'end_html' => '</div>',
                'actions' => array(
                    array(
                        'route' => 'olix_security_manager_user_edit',
                        'route_parameters' => array('username' => 'username'),
                        'icon' => 'fa fa-edit fa-fw',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Modifier cet utilisateur',
                            'class' => 'btn btn-primary btn-xs btn-update',
                            'role' => 'button'
                        ),
                    ),
                    array(
                        'route' => 'olix_security_manager_user_delete',
                        'route_parameters' => array('username' => 'username'),
                        'icon' => 'fa fa-remove fa-fw',
                        'label' => '',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Supprimer cet utilisateur',
                            'class' => 'btn btn-danger btn-xs btn-delete',
                            'role' => 'button',
                            'onclick' => 'return olixAdminInterface.confirmDelete(this)'
                        ),
                    ),
                    array(
                        'route' => 'olix_security_manager_user_changepwd',
                        'route_parameters' => array('username' => 'username'),
                        'icon' => 'fa fa-key fa-fw',
                        'label' => '',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Modifier le mot de passe',
                            'class' => 'btn btn-dark btn-xs btn-action',
                            'role' => 'button'
                        ),
                    )
                )
            ))
        ;
    }


    /**
     * @see \Sg\DatatablesBundle\Datatable\View\DatatableViewInterface::getEntity()
     */
    public function getEntity()
    {
        return;
    }


    /**
     * @see \Sg\DatatablesBundle\Datatable\View\DatatableViewInterface::getName()
     */
    public function getName()
    {
        return "user_datatable";
    }

}