<?php

namespace Dof\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Dof\CMSBundle\ArticleType;

class ArticleAdmin extends Admin
{
    protected $locales = [
      'fr',
      'en',
      'de',
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
          ->add('type', 'choice', array('choices' => $typeValues))
          ->add('published', null, array('required' => false))
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
