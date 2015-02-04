<?php
/**
 * Formulaire de la gestion du groupe
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class GroupFormType extends AbstractType
{

    /**
     * Classe de l'entity User
     * @var string
     */
    private $class;

    /**
     * Liste des Rôles
     * @var array
     */
    private $roles = array();


    /**
     * @param string $class
     * @param array  $roles
     */
    public function __construct($class,  $roles)
    {
        $this->class = $class;
        $this->roles = $roles;
    }


    /**
     * Retourne la liste brute des rôles
     * 
     * @return array
     */
    private function getRoles()
    {
        $result = array();
         
        foreach ($this->roles as $name => $rolesHierarchy) {
            $result[$name] = $name;
             
            foreach ($rolesHierarchy as $role) {
                if (!isset($result[$role])) {
                    $result[$role] = $role;
                }
            }
        }
        
        return $result;
    }


    /**
     * Construction du formulaire
     *
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Nom du groupe'
            ))
            ->add('roles', 'choice', array(
                'label' => 'Rôles',
                'choices' => $this->getRoles(),
                'multiple' => true,
                'expanded' => true
            ))
            ->add('users', 'olix_doublelist_entity', array(
                'label' => 'Utilisateurs',
                'class' => 'OlixSecurityBundle:User',
                'property' => 'name',
                'mapped' => false
            ))
        ;
    }


    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'group',
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'olix_security_group';
    }

}