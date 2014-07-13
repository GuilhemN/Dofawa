<?php

namespace Dof\GraphicsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Dof\CharactersBundle\Gender;
use Dof\GraphicsBundle\LivingItem;

class CharacterLookType extends AbstractType
{
    protected $container;

    protected $securityContext;
    protected $requestStack;
    protected $translator;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->securityContext = $this->container->get('security.context');
        $this->requestStack = $this->container->get('request_stack');
        $this->translator = $this->container->get('translator');
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

        if(($weapon = $entity->getWeapon()) !== null)
            $weapon = $weapon->getId();
        if(($shield = $entity->getShield()) !== null)
            $shield = $shield->getId();
        if(($hat = $entity->getHat()) !== null){
            if($hat instanceOf LivingItem)
                $hat = $hat->getTemplate()->getId().'/'.$hat->getLevel();
            else
                $hat = $hat->getId();
        }
        if(($cloak = $entity->getCloak()) !== null)
            if($cloak instanceOf LivingItem)
                $cloak = $cloak->getTemplate()->getId().'/'.$cloak->getLevel();
            else
                $cloak = $cloak->getId();
        if(($animal = $entity->getAnimal()) !== null)
            $animal = $animal->getId();
        if(($face = $entity->getFace()) !== null)
            $face = $face->getLabel();
        $builder
            ->add('name', null, array('label' => $this->translator->trans('name', [], 'generalTrans'), 'required' => true))
            ->add('breed', null, array('label' => $this->translator->trans('breed', [], 'breed'), 'property' => $fieldName, 'required' => true))
            ->add('gender', 'choice', array(
                  'label' => 'gender',
                  'choices'   => array_flip(Gender::getValues()),
                  'required'  => true,
                  'expanded'  => true,
                  'translation_domain' => 'gender'
              ))
            ->add('face', 'choice', array(
                'label' => $this->translator->trans('face', [], 'face'),
                'choices' => array('I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV', 'V' => 'V', 'VI' => 'VI', 'VII' => 'VII', 'VIII' => 'VIII'),
                'required' => true,
                'mapped' => false,
                'data' => $face
              ))
            ->add('weapon', 'text', array('label' => $this->translator->transChoice('weapons.main', 1, [], 'type_item'), 'required' => false, 'mapped' => false, 'data' => $weapon))
            ->add('shield', 'text', array('label' => $this->translator->transChoice('equipments.shield', 1, [], 'type_item'), 'required' => false, 'mapped' => false, 'data' => $shield))
            ->add('hat', 'text', array('label' => $this->translator->transChoice('equipments.hat', 1, [], 'type_item'), 'required' => false, 'mapped' => false, 'data' => $hat))
            ->add('cloak', 'text', array('label' => $this->translator->transChoice('equipments.cloak', 1, [], 'type_item'), 'required' => false, 'mapped' => false, 'data' => $cloak))
            ->add('animal', 'text', array('label' => $this->translator->transChoice('animals.main', 1, [], 'type_item'), 'required' => false, 'mapped' => false, 'data' => $animal))
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
