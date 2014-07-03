<?php

namespace Dof\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
          ->add('username')
          ->add('email')
          ->add('enabled')
          ->add('locked')
          ->add('roles')
          ->add('point')
          ->add('groupe')
          ->add('guilde')
          ->add('lieu')
          ->add('site')
          ->add('born')
          ->add('presentation')
          ->add('horaires')
          ->add('differentpseudo')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('locked')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('enabled')
            ->add('locked')
        ;
    }
}
