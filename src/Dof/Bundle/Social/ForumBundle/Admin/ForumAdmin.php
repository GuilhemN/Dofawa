<?php

namespace Dof\Bundle\Social\ForumBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ForumAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('category')
            ->add('index')
            ->add('slug')
            ->add('nameFr')
            ->add('nameEn')
            ->add('nameDe')
            ->add('nameEs')
            ->add('nameIt')
            ->add('namePt')
            ->add('nameJa')
            ->add('nameRu')
            ->add('descriptionFr')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('category')
            ->add('index')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('slug')
            ->add('nameFr')
            ->add('descriptionFr')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('index')
            ->add('category')
            ->add('nameFr')
            ->add('nameEn')
            ->add('nameDe')
            ->add('nameEs')
            ->add('nameIt')
            ->add('namePt')
            ->add('nameJa')
            ->add('nameRu')
            ->add('descriptionFr')
            ->add('descriptionEn')
            ->add('descriptionDe')
            ->add('descriptionEs')
            ->add('descriptionIt')
            ->add('descriptionPt')
            ->add('descriptionJa')
            ->add('descriptionRu')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('category')
            ->add('index')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('slug')
            ->add('nameFr')
            ->add('nameEn')
            ->add('nameDe')
            ->add('nameEs')
            ->add('nameIt')
            ->add('namePt')
            ->add('nameJa')
            ->add('nameRu')
            ->add('descriptionFr')
            ->add('descriptionEn')
            ->add('descriptionDe')
            ->add('descriptionEs')
            ->add('descriptionIt')
            ->add('descriptionPt')
            ->add('descriptionJa')
            ->add('descriptionRu')
        ;
    }
}