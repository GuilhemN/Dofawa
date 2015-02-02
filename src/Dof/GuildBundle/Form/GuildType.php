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
            ->add('name', null,array('label' => 'name_guild', 'translation_domain' => 'guild'))
            ->add('server', null,array('label' => 'serv', 'translation_domain' => 'guild'))
            ->add('lvlguild', null,array('label' => 'lvl_guild', 'translation_domain' => 'guild','attr' => array('min' => '1', 'max' => '200', 'step' => '1')))
            ->add('lvlmini', null,array('label' => 'lvl_min_rec', 'translation_domain' => 'guild','attr' => array('min' => '1', 'max' => '200', 'step' => '1')))
            ->add('leader', null,array('label' => 'leader', 'translation_domain' => 'guild'))
            ->add('recruitment', null, array('required' => false, 'label' => 'recruitment', 'translation_domain' => 'guild'))
            ->add('speciality', null,array('required' => false,'label' => 'specialty', 'translation_domain' => 'guild'))
            ->add('description', null, array('label' => 'description', 'translation_domain' => 'guild'))
            ->add('forum', null,array('label' => 'forum', 'translation_domain' => 'guild','required' => false)) 
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
