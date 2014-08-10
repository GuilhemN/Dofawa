<?php

namespace Dof\BuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlayerCharacterType extends AbstractType
{
    public function __construct() {}

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('level')
            ->add('breed')
            ->add('stuff')
            ->add('submit', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dof\BuildBundle\Entity\PlayerCharacter'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dof_buildbundle_playercharacter';
    }
}
