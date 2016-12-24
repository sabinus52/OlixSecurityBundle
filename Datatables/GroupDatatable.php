<?php
/**
 * DataTables de la gestion des groupes
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Datatables;

use Olix\DatatablesBootstrapBundle\Datatable\View\AbstractDatatableView;


class GroupDatatable extends AbstractDatatableView
{

    /**
     * Entité des groupes "fos_user.group.group_class" (config.yml)
     * @var string
     */
    private $entity;


    /**
     * Construction de la DataTable
     * 
     * {@inheritDoc}
     * @see \Sg\DatatablesBundle\Datatable\View\DatatableViewInterface::buildDatatable()
     */
    public function buildDatatable(array $options = array())
    {
        $this->entity = $options['entity'];
        
        $this->ajax->set(array(
            'url' => $this->router->generate('olix_security_manager_group_results'),
        ));
        
        // Déclarations des colonnes
        $this->getColumnBuilder()
            ->add('id', 'column', array(
                  'title' => 'Id',
                  'class' => 'text-center',
            ))
            ->add('name', 'column', array(
                  'title' => 'Nom',
            ))
            ->add('roles', 'column', array(
                  'title' => 'Rôles',
            ))
            // Boutons Actions
            ->add(null, 'action', array(
                'title' => '',
                'start_html' => '<div class="olix-actions">',
                'end_html' => '</div>',
                'actions' => array(
                    array(
                        'route' => 'olix_security_manager_group_edit',
                        'route_parameters' => array('id' => 'id'),
                        'icon' => 'fa fa-edit fa-fw',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Modifier ce groupe',
                            'class' => 'btn btn-primary btn-xs btn-update',
                            'role' => 'button',
                        ),
                    ),
                    array(
                        'route' => 'olix_security_manager_group_delete',
                        'route_parameters' => array('id' => 'id'),
                        'icon' => 'fa fa-remove fa-fw',
                        'label' => '',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Supprimer ce groupe',
                            'class' => 'btn btn-danger btn-xs btn-delete',
                            'role' => 'button',
                            'onclick' => 'return olixAdminInterface.confirmDelete(this)',
                        ),
                    ),
                )
            ))
        ;
    }


    /**
     * @see \Sg\DatatablesBundle\Datatable\View\DatatableViewInterface::getEntity()
     */
    public function getEntity()
    {
        return $this->entity;
    }


    /**
     * @see \Sg\DatatablesBundle\Datatable\View\DatatableViewInterface::getName()
     */
    public function getName()
    {
        return "group_datatable";
    }

}