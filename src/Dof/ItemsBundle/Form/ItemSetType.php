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
            ->add('id')
            ->add('level')
            ->add('itemCount')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('slug')
            ->add('release')
            ->add('preliminary')
            ->add('deprecated')
            ->add('nameFr')
            ->add('nameEn')
            ->add('nameDe')
            ->add('nameEs')
            ->add('nameIt')
            ->add('namePt')
            ->add('nameJp')
            ->add('nameRu')
            ->add('primaryBonus')
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
