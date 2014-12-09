<?php

namespace Dof\ItemsManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Dof\ItemsManagerBundle\InventorySlot;

class InventorySearch extends AbstractType
{
    private $isTypeUpdatable;
    private $defaultTypes;

    public function __construct($isTypeUpdatable = true){
        $this->isTypeUpdatable = $isTypeUpdatable;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('get')
            ->add('name', 'text', ['label' => 'name', 'translation_domain' => 'generalTrans', 'trim' => true, 'required' => false])
            ->add('type', 'choice', array(
                'disabled' => !$this->isTypeUpdatable,
                'choices' => InventorySlot::getPrefixedNames('equipments.'),
                'multiple' => true,
                'attr'=> array('class' => 'to-select2', 'data-placeholder' => 'Sélectionner un type d\'item'),
                'translation_domain' => 'type_item'
                ))
            ->add('maj', 'text', ['required' => false, 'attr' => ['placeholder' => 'ex: 2.22']])
            ->add('submit', 'submit',array('label' => 'search', 'translation_domain' => 'FOSMessageBundle'))
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
