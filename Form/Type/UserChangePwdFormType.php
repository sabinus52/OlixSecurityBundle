<?php
/**
 * Formulaire de modification de mot de passe d'un utilisateur pour le manager
 * 
 * @author Olivier <sabinus52@gmail.com>
 * 
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class UserChangePwdFormType extends UserBaseFormType
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
        // Conserve que le champ mot de passe
        foreach (array_keys($builder->all()) as $name) {
            if ($name != 'plainPassword') $builder->remove($name);
        }
    }

}
