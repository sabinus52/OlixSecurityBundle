<?php
/**
 * Formulaire de création d'un utilisateur pour le manager
 * 
 * @author Olivier <sabinus52@gmail.com>
 * 
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;


class UserCreateFormType extends UserBaseFormType
{

    /**
     * Construction du formulaire
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('locked');
        $builder->remove('expired');
        $builder->remove('expiresAt');
    }

}
