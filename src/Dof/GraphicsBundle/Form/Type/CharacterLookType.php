<?php

namespace Dof\GraphicsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Dof\CharactersBundle\Gender;

class CharacterLookType extends AbstractType
{
    protected $securityContext;
    protected $requestStack;

    public function __construct(SecurityContextInterface $securityContext, RequestStack $requestStack)
    {
        $this->securityContext = $securityContext;
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $request = $this->requestStack->getCurrentRequest();
        $fieldName = 'name'.ucfirst($request->getLocale());


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $entity = $event->getData();
            $form = $event->getForm();

            if (!$entity || null === $entity->getId() // Si nouveau
                or $this->securityContext->isGranted('ROLE_STYLIST_ADMIN') // Si admin
                or ($this->securityContext->isGranted('ROLE_STYLIST') && // Si styliste et propriétaire
                    $this->securityContext->getToken()->getUser()->getId()
                    == $entity->getOwner()->getId())
              ) {
                $form->add('publiclyVisible', 'checkbox', array('required' => false));
            }
        });

        $entity = $builder->getData();

        if($weapon = $entity->getWeapon() != null)
            $weapon = $weapon->getId();
        if($shield = $entity->getShield() != null)
            $shield = $shield->getId();
        if($hat = $entity->getHat() != null)
            $hat = $hat->getId();
        if($cloak = $entity->getCloak() != null)
            $cloak = $cloak->getId();
        if($animal = $entity->getAnimal() != null)
            $animal = $animal->getId();

        $builder
            ->add('name', null, array('label' => 'name', 'translation_domain' => 'generalTrans'))
            ->add('breed', null, array('label' => 'breed', 'property' => $fieldName, 'translation_domain' => 'breed'))
            ->add('gender', 'choice', array(
                  'label' => 'gender',
                  'choices'   => array_flip(Gender::getValues()),
                  'required'  => true,
                  'expanded'  => true,
                  'translation_domain' => 'gender'
              ))
            ->add('face', 'choice', array(
                'label' => 'face',
                'choices' => array('I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV', 'V' => 'V', 'VI' => 'VI', 'VII' => 'VII', 'VIII' => 'VIII'),
                'required' => true,
                'mapped' => false,
                'data' => $entity->getFace()->getLabel(),
                'translation_domain' => 'face'
              ))
            ->add('weapon', 'text', array('required' => false, 'mapped' => false, 'data' => $weapon))
            ->add('shield', 'text', array('required' => false, 'mapped' => false, 'data' => $shield))
            ->add('hat', 'text', array('required' => false, 'mapped' => false, 'data' => $hat))
            ->add('cloak', 'text', array('required' => false, 'mapped' => false, 'data' => $cloak))
            ->add('animal', 'text', array('required' => false, 'mapped' => false, 'data' => $animal))
            ->add('colors', 'collection', array(
                'options'  => array(
                    'required'  => false,
                ),
            ))
            ->add('submit', 'submit')
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
