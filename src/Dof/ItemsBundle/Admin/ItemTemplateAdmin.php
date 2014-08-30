<?php

namespace Dof\ItemsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ItemTemplateAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('dominantColor')
            ->add('level')
            ->add('visible')
            ->add('gatheringJobMinLevel')
            ->add('slug')
            ->add('release')
            ->add('preliminary')
            ->add('deprecated')
            ->add('nameFr')
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
            ->add('level')
            ->add('nameFr')
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
            ->add('nameFr', null, ['disabled' => true])
            ->add('obtainmentFr')
            ->add('obtainmentEn')
            ->add('obtainmentDe')
            ->add('obtainmentEs')
            ->add('obtainmentIt')
            ->add('obtainmentPt')
            ->add('obtainmentJp')
            ->add('obtainmentRu')
            ->add('visible')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('obtainmentFr')
            ->add('obtainmentEn')
            ->add('obtainmentDe')
            ->add('obtainmentEs')
            ->add('obtainmentIt')
            ->add('obtainmentPt')
            ->add('obtainmentJp')
            ->add('obtainmentRu')
            ->add('iconRelativePath')
            ->add('dominantColor')
            ->add('criteria')
            ->add('level')
            ->add('weight')
            ->add('tradeable')
            ->add('npcPrice')
            ->add('visible')
            ->add('gatheringJobMinLevel')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('slug')
            ->add('release')
            ->add('preliminary')
            ->add('deprecated')
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
