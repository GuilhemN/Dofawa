<?php

namespace Dof\ItemsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Dof\ItemsBundle\ItemSlot;

class ItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('get')
            ->add('type', 'choice', array(
                'choices' => ItemSlot::getPrefixedNames('equipments.'),
                'multiple' => true,
                'attr'=> array('class' => 'to-select2', 'data-placeholder' => 'Sélectionner un type d\'item'),
                'translation_domain' => 'type_item'
                ))
            ->add('submit', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'items';
    }
}
