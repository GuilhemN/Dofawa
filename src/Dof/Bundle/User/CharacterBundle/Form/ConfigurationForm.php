<?php

namespace Dof\Bundle\User\CharacterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Dof\Bundle\CharacterBundle\Gender;

class ConfigurationForm extends AbstractType
{
    private $locale;

    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $caractOptions = [
                'precision' => 0,
                'attr' => array('min' => '0', 'max' => '1100', 'step' => '1'),
                'constraints' => new Assert\Range(['min' => 0, 'max' => 1100]),
                'translation_domain' => 'item',
            ];
        $builder
            // Stuff
            ->add('title', 'text', ['required' => true, 'trim' => true, 'label' => 'title', 'translation_domain' => 'generalTrans'])
            ->add('stuffVisibility', 'checkbox', ['label' => 'Public', 'required' => false])
            ->add('vitality', 'number', $caractOptions + ['label' => 'Vitality', 'translation_domain' => 'item'])
            ->add('wisdom', 'number', $caractOptions + ['label' => 'Wisdom', 'translation_domain' => 'item'])
            ->add('strength', 'number', $caractOptions + ['label' => 'Strength', 'translation_domain' => 'item'])
            ->add('intelligence', 'number', $caractOptions + ['label' => 'Intelligence', 'translation_domain' => 'item'])
            ->add('chance', 'number', $caractOptions + ['label' => 'Chance', 'translation_domain' => 'item'])
            ->add('agility', 'number', $caractOptions + ['label' => 'Agility', 'translation_domain' => 'item'])

            // Character
            ->add('name', 'text', ['required' => true, 'trim' => true, 'label' => 'name', 'translation_domain' => 'generalTrans'])
            ->add('characterVisibility', 'checkbox', ['label' => 'Public', 'required' => false])
            ->add('level', 'number', array(
                'attr' => array('min' => '1', 'max' => '200', 'step' => '1'),
                'constraints' => new Assert\Range(['min' => 1, 'max' => 200]), 'label' => 'list.level', 'translation_domain' => 'item',
                )
            )
            ->add('breed', 'entity', ['class' => 'DofCharacterBundle:Breed', 'property' => 'name', 'label' => 'breed', 'translation_domain' => 'breed'])
            ->add('gender', 'choice', array(
                  'label' => 'gender',
                  'choices' => array_flip(Gender::getValues()),
                  'required' => true,
                  'expanded' => true,
                  'translation_domain' => 'gender',
              ))
            ->add('face', 'choice', array(
                'label' => 'face',
                'choices' => array('I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV', 'V' => 'V', 'VI' => 'VI', 'VII' => 'VII', 'VIII' => 'VIII'),
                'required' => true,
                'translation_domain' => 'face',
            ))
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
        return 'dof_user_characterbundle_configuration_form';
    }
}
