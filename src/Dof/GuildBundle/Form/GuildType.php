<?php

namespace Dof\GuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class GuildType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null,array('label' => 'Nom de la guilde'))
            ->add('serveur')
            ->add('lvlguild', null,array('label' => 'Niveau de la guilde','attr' => array('min' => '1', 'max' => '200', 'step' => '1')))
            ->add('lvlmini', null,array('label' => 'Niveau minimum de recrutement','attr' => array('min' => '1', 'max' => '200', 'step' => '1')))
            ->add('leader', null,array('label' => 'Meneur'))
            ->add('recruitment', null, array('required' => false, 'label' => 'Recrutement (on/off)'))
            ->add('speciality', null,array('required' => false,'label' => 'Spécialité de la guilde'))
            ->add('description')
            ->add('forum', null,array('required' => false)) 
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dof\GuildBundle\Entity\Guild'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dof_guildbundle_guild';
    }
}
