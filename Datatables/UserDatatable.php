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

use Olix\DatatablesBootstrapBundle\Datatable\View\AbstractDatatableView;


class UserDatatable extends AbstractDatatableView
{

    /**
     * Entité des utilisateurs "fos_user.group.group_class" (config.yml)
     * @var string
     */
    private $entity;

    /**
     * Délai avant d'être hors ligne
     * @var integer
     */
    private $delayOnline;


    /**
     * Verifie si l'utilisateur est en activité
     *
     * @param integer $minDelay Minutes d'inactivité
     * @return boolean
     */
    private function isOnline($lastActivity)
    {
        $delay = new \DateTime();
        $strDelay = sprintf('%s minutes ago', $this->delayOnline);
        $delay->setTimestamp(strtotime($strDelay));
        return ($lastActivity > $delay);
    }


    /**
     * Retourne le temps écoulé depuis la dernière connexion
     *
     * @return string
     */
    private function getIntervalLastLogin($lastLogin)
    {
        if (!$lastLogin) return '';
        $now = new \DateTime();
        $interval = $now->diff($lastLogin);
        if ($interval->days == 1)
            return $interval->format('%a jour');
        elseif ($interval->days > 1)
            return $interval->format('%a jours');
        elseif ($interval->h == 1)
            return $interval->format('%h heure');
        elseif ($interval->h > 1)
            return $interval->format('%h heures');
        elseif ($interval->i == 1)
            return $interval->format('%i minute');
        else
            return $interval->format('%i minutes');
    }


    /**
     * {@inheritDoc}
     * @see \Sg\DatatablesBundle\Datatable\View\AbstractDatatableView::getLineFormatter()
     */
    public function getLineFormatter()
    {
        $formatter = function($line) {
            $line['online'] = $this->isOnline($line['lastActivity']);
            $line['intervalLastLogin'] = $this->getIntervalLastLogin($line['lastLogin']);
            $line['lastActivity'] = '';
            return $line;
        };
        
        return $formatter;
    }


    /**
     * Construction de la DataTable
     * 
     * {@inheritDoc}
     * @see \Sg\DatatablesBundle\Datatable\View\DatatableViewInterface::buildDatatable()
     */
    public function buildDatatable(array $options = array())
    {
        $this->entity = $options['entity'];
        $this->delayOnline = $options['delay'];
        
        $this->ajax->set(array(
            'url' => $this->router->generate('olix_security_manager_user_results'),
        ));
        
        // Déclarations des colonnes
        $this->getColumnBuilder()
            ->add('id', 'column', array(
                  'title' => 'Id',
                  'class' => 'text-center',
                  'width' => '10px'
            ))
            ->add('avatar', 'column', array(
                  'title' => 'Avatar',
                  'class' => 'text-center',
                  'render' => 'render_column_avatar',
            ))
            ->add('online', 'virtual', array(
                  'title' => 'Connecté',
                  'class' => 'text-center',
                  'render' => 'render_column_online',
            ))
            ->add('name', 'column', array(
                  'title' => 'Nom',
            ))
            ->add('username', 'column', array(
                  'title' => 'Login',
            ))
            ->add('locked', 'column', array(
                  'title' => 'Statut',
                  'class' => 'text-center',
                  'render' => 'render_column_statut',
            ))
            ->add('expired', 'column', array(
                  'visible' => false,
            ))
            ->add('lastLogin', 'datetime', array(
                  'title' => 'Dernière connexion',
                  'date_format' => 'DD/MM/YYYY HH:mm',
                  'render' => 'render_column_login',
            ))
            ->add('lastActivity', 'column', array(
                  'searchable' => false,
                  'visible' => false,
            ))
            ->add('intervalLastLogin', 'virtual', array(
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
                            'role' => 'button',
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
                            'onclick' => 'return olixAdminInterface.confirmDelete(this)',
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
                            'role' => 'button',
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
        return $this->entity;
    }


    /**
     * @see \Sg\DatatablesBundle\Datatable\View\DatatableViewInterface::getName()
     */
    public function getName()
    {
        return "user_datatable";
    }

}