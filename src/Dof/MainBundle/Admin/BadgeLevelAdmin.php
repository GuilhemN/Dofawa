<?php

namespace Dof\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class BadgeLevelAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('minCount')
            ->add('path')
            ->add('nameFr')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('nameFr')
            ->add('minCount')
            ->add('path')
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
            ->add('badge', null, array('required' => false))
            ->add('minCount')
            ->add('file', 'file', array('required' => false))
            ->add('nameFr')
            ->add('nameEn')
            ->add('nameDe')
            ->add('nameEs')
            ->add('nameIt')
            ->add('namePt')
            ->add('nameJp')
            ->add('nameRu')
            ->add('descriptionFr')
            ->add('descriptionEn')
            ->add('descriptionDe')
            ->add('descriptionEs')
            ->add('descriptionIt')
            ->add('descriptionPt')
            ->add('descriptionJp')
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
            ->add('createdAt')
            ->add('updatedAt')
            ->add('minCount')
            ->add('path')
            ->add('nameFr')
            ->add('nameEn')
            ->add('nameDe')
            ->add('nameEs')
            ->add('nameIt')
            ->add('namePt')
            ->add('nameJp')
            ->add('nameRu')
            ->add('descriptionFr')
            ->add('descriptionEn')
            ->add('descriptionDe')
            ->add('descriptionEs')
            ->add('descriptionIt')
            ->add('descriptionPt')
            ->add('descriptionJp')
            ->add('descriptionRu')
        ;
    }
}
