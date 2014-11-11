<?php

namespace Dof\BuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Dof\CharactersBundle\Gender;

class ConfigurationForm extends AbstractType
{
    private $locale;

    public function __construct($locale) {
        $this->locale = $locale;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Stuff
            ->add('title', 'text', ['required' => true, 'trim' => true])
            ->add('stuffVisibility', 'checkbox', ['label' => 'Visible', 'required' => false])

            // Character
            ->add('name', 'text', ['required' => true, 'trim' => true])
            ->add('characterVisibility', 'checkbox', ['label' => 'Visible', 'required' => false])
            ->add('level', 'number', array(
                'attr' => array('min' => '1', 'max' => '200', 'step' => '1'))
            )
            ->add('breed', 'entity', ['class' => 'DofCharactersBundle:Breed', 'property' => 'name' . ucfirst($this->locale)])
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
