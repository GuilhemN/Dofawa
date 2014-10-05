<?php

namespace Dof\GuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('lvlguild', null,array('label' => 'Niveau de la guilde'))
            ->add('lvlmini', null,array('label' => 'Niveau minimum de recrutement'))
            ->add('leader', null,array('label' => 'Meneur'))
            ->add('recruitment', null, array('required' => false, 'label' => 'Recrutement (on/off)'))
            ->add('speciality', null,array('label' => 'Spécialité de la guilde'))
            ->add('description')
            ->add('forum') 
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
