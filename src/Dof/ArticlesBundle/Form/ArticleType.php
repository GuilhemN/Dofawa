<?php

namespace Dof\ArticlesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Dof\ArticlesBundle\ArticleType as ArticleEnum;

class ArticleType extends AbstractType
{
    protected $rs;

    public function __construct(RequestStack $rs)
    {
        $this->rs = $rs;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $req = $this->rs->getCurrentRequest();

        $builder
            ->add('name' . $req->getLocale(), null, array('label' => 'Titre'))
            ->add('keys', null, array('label' => 'Mots clés'))
            ->add('category', null, array('required' => false,'label' => 'Categorie'))
            ->add('type', 'choice', [ 'choices' => array_flip(ArticleEnum::getValues())])
            ->add('description' . $req->getLocale(), 'textarea')
            ->add('submit', 'Valider')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dof\ArticlesBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dof_articlesbundle_article';
    }
}
