<?php

namespace Dof\BuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Dof\CharactersBundle\Gender;

class ConfigurationForm extends AbstractType
{
    public function __construct() {}

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Stuff
            ->add('title', 'text', ['required' => true, 'trim' => true])
            ->add('stuffVisibility', 'checkbox')

            // Character
            ->add('name', 'text', ['required' => true, 'trim' => true])
            ->add('characterVisibility', 'checkbox')
            ->add('level', 'number', array(
                'data' => '1',
                'attr' => array('min' => '1', 'max' => '200', 'step' => '1'))
            )
            // ->add('breed')
            ->add('gender', 'choice', array(
                  'label' => 'gender',
                  'choices'   => array_flip(Gender::getValues()),
                  'required'  => true,
                  'expanded'  => true,
                  'mapped' => false,
                  'translation_domain' => 'gender'
              ))
            ->add('face', 'choice', array(
                'label' => 'face',
                'choices' => array('I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV', 'V' => 'V', 'VI' => 'VI', 'VII' => 'VII', 'VIII' => 'VIII'),
                'required' => true,
                'mapped' => false,
                'translation_domain' => 'face'
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dof_buildbundle_configuration_form';
    }
}
