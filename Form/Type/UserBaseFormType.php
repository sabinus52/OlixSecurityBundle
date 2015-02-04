<?php
/**
 * Formulaire de la gestion de l'utilisateur
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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


class UserBaseFormType extends AbstractType
{

    /**
     * Classe de l'entity User
     * @var string
     */
    private $class;


    /**
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }


    /**
     * Construction du formulaire
     *
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'label' => 'Identifiant'
            ))
            ->add('name', 'text', array(
                'label' => 'Son nom'
            ))
            ->add('email', 'email', array(
                'label' => 'Son email'
            ))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Mot de passe (vérification)'),
                'constraints' => array(
                    new NotBlank(),
                    new Length(array(
                        'min' => 6,
                        'max' => 4090,
                        'minMessage' => "Le mot de passe doit faire au moins {{ limit }} caractères",
                        'maxMessage' => "Le mot de passe ne peut pas être plus long que {{ limit }} caractères"
            )))))
            ->add('locked', 'olix_switch', array(
                'label' => 'Bloquage de l\'utilisateur',
                'attr' => array(
                    'data-on-text' => "OUI",
                    'data-off-text' => "NON",
                    'data-on-color' => 'danger',
                    'data-off-color' => 'success',
            )))
            ->add('expiresAt', 'olix_datetimepicker', array(
                'label' => 'Le compte expire le',
                'mapped' => false,
                'format' => 'dd/MM/yyyy HH:mm',
                'config' => array(
                    'pickerPosition' => 'bottom-left',
                    'minuteStep' => 5
            )))
            ->add('groups', 'olix_doublelist_entity', array(
                'label' => 'Groupes',
                'class' => 'OlixSecurityBundle:Group',
                'property' => 'name',
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
            'intention'  => 'user',
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'olix_security_user';
    }

}