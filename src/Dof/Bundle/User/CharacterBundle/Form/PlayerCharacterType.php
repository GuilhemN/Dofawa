<?php

namespace Dof\Bundle\User\CharacterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Dof\Bundle\CharacterBundle\Gender;

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
            ->add('name', null, array('label' => 'name', 'translation_domain' => 'generalTrans'))
            ->add('level', null, array(
                'data' => '1',
                'attr' => array('min' => '1', 'max' => '200', 'step' => '1'), 'label' => 'list.level', 'translation_domain' => 'item')
            )
            ->add('breed', null, array('label' => 'breed', 'translation_domain' => 'breed'))
            ->add('gender', 'choice', array(
                  'label' => 'gender',
                  'choices'   => array_flip(Gender::getValues()),
                  'required'  => true,
                  'expanded'  => true,
                  'mapped' => false,
                  'translation_domain' => 'gender'
              ))
            ->add('face', 'choice', array(
                'label' => 'face',
                'choices' => array('I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV', 'V' => 'V', 'VI' => 'VI', 'VII' => 'VII', 'VIII' => 'VIII'),
                'required' => true,
                'mapped' => false,
                'translation_domain' => 'face'
              ))
            ->add('submit', 'submit',array('label' => 'create', 'translation_domain' => 'generalTrans'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dof\Bundle\User\CharacterBundle\Entity\PlayerCharacter'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dof_user_characterbundle_playercharacter';
    }
}
