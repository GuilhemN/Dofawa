<?php

namespace Dof\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nameFr', 'text', array('label' => 'Fr Title'))
            ->add('nameEn', 'text', array('label' => 'En Title'))
            ->add('nameDe', 'text', array('label' => 'De Title'))
            ->add('nameEs', 'text', array('label' => 'Es Title'))
            ->add('nameIt', 'text', array('label' => 'It Title'))
            ->add('namePt', 'text', array('label' => 'Pt Title'))
            ->add('nameJp', 'text', array('label' => 'Jp Title'))
            ->add('nameRu', 'text', array('label' => 'Ru Title'))

            ->add('creator', 'entity', array('class' => 'Dof\UserBundle\Entity\User'))
            ->add('descriptionFr') //if no type is specified, SonataAdminBundle tries to guess it
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
