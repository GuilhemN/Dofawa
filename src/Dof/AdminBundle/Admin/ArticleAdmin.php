<?php

namespace Dof\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleAdmin extends Admin
{
    protected $locales;

    public function __construct($code, $class, $baseControllerName, $locales)
      parent::__construct($code, $class, $baseControllerName);

      $this->locales = $locales;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
          ->add('creator', 'entity', array('class' => 'Dof\UserBundle\Entity\User'))

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
            ->add('creator')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('nameFr')
            ->add('slug')
            ->add('creator')
        ;
    }
}
