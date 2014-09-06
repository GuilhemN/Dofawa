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
            ->add('level')
            ->add('itemCount')
            ->add('release')
            ->add('preliminary')
            ->add('nameFr')
            ->add('primaryBonus')
            ->add('submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dof\ItemsBundle\Entity\ItemSet'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dof_itemsbundle_itemset';
    }
}
