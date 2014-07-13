<?php

namespace Dof\TranslationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TranslationType extends AbstractType
{

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->translator = $this->container->get('translator');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();

        $builder
            ->add('label', null, array( 'disabled' => true ))
            ->add('nameFr', null, array( 'data' => $this->translator->trans($entity->getLabel(), array(), $entity->getDomain(), 'fr'), 'disabled' => true, 'mapped' => false ))
            ->add('translation')
            ->add('submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dof\TranslationBundle\Entity\Translation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dof_translation';
    }
}
