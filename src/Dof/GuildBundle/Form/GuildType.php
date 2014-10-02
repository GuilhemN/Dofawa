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
            ->add('name')
            ->add('serveur')
            ->add('lvlguild')
            ->add('lvlmini')
            ->add('leader')
            ->add('recruitment')
            ->add('speciality')
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
