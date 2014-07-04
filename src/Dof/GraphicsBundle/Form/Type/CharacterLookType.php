<?php

namespace Dof\GraphicsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Security\Core\SecurityContextInterface;

use Dof\CharactersBundle\Gender;

class CharacterLookType extends AbstractType
{
    protected $securityContext;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $entity = $event->getData();
            $form = $event->getForm();

            if (!$entity || null === $entity->getId() // Si nouveau
                or $this->securityContext->isGranted('ROLE_STYLIST_ADMIN') // Si admin
                or ($this->securityContext->isGranted('ROLE_STYLIST') && // Si styliste et propriétaire
                    $this->securityContext->getToken()->getUser()->getId()
                    == $entity->getOwner()->getId())
              ) {
                $form->add('publiclyVisible');
            }
        });

        $builder
            ->add('breed')
            ->add('gender', 'choice', array(
                  'choices'   => array_flip(Gender::getValues()),
                  'required'  => true,
              ))
            ->add('face', null, array('required' => false))
            ->add('weapon', null, array('required' => false))
            ->add('shield', null, array('required' => false))
            ->add('hat', null, array('required' => false))
            ->add('cloak', null, array('required' => false))
            ->add('animal', null, array('required' => false))
            ->add('colors', 'collection', array(
                // ces options sont passées à chaque champ
                'options'  => array(
                    'required'  => false,
                    //'attr'      => array('class' => 'email-box')
                ),
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dof\GraphicsBundle\Entity\CharacterLook',
        ));
    }

    public function getName()
    {
        return 'character_look';
    }
}
