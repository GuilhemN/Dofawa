<?php

namespace Dof\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Dof\ArticlesBundle\ArticleType;

class ArticleAdmin extends Admin
{
    protected $locales = [
      'en',
      'de',
      'fr',
      'es',
      'ru',
      'it'
    ];


    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $typeValues = array_flip(ArticleType::getValues());

        $formMapper
          ->add('creator')
          ->add('keys')
          ->add('category')
          ->add('type', 'choice', array(
            'choices'   => $typeValues)
          ->add('published', 'choice', array(
            'choices'   => array(
                '0'   => 'Non',
                '1' => 'Oui',
            ))
        ;



        foreach($this->locales as $locale)
          $formMapper
              ->add('name'.ucfirst($locale))
              ->add('description'.ucfirst($locale))
          ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nameFr')
            ->add('owner')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('nameFr')
            ->add('slug')
            ->add('owner')
        ;
    }
}
