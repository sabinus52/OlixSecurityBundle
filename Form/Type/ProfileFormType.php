<?php
/**
 * Formulaire du profil de l'utilisateur
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseFormType;
use Symfony\Component\Form\FormBuilderInterface;


class ProfileFormType extends BaseFormType {


    /**
     * Construction du formulaire
     *
     * @see \FOS\UserBundle\Form\Type\ProfileFormType::buildForm()
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', 'text', array('label' => 'Nom :'))
        ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
        ;
    }


}