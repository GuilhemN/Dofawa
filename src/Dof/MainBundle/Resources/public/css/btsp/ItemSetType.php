<?php

namespace Dof\ItemsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemSetType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('level', 'number', array('required' => false))
            ->add('itemCount', 'number', array('required' => false))
            ->add('release', 'checkbox', array('required' => false))
            ->add('preliminary', 'checkbox', array('required' => false))
            ->add('nameFr', 'text', array('required' => false))
            ->add('submit', 'submit')
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
        return 'dof_itemsbundle_itemset';
    }
}
