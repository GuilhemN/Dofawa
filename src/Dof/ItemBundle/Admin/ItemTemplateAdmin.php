<?php

namespace Dof\ItemBundle\Admin;

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
            ->add('nameFr')
            ->add('descriptionFr')
            ->add('level')
            ->add('visible')
            ->add('slug')
            ->add('release')
            ->add('preliminary')
            ->add('deprecated')
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
            ->add('release')
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
            ->add('file', 'file')
            ->add('nameFr', null, ['disabled' => true])
            ->add('visible', null, ['required' => false])
            ->add('release', null, ['required' => false])
            ->add('preliminary', null, ['required' => false])
            ->add('deprecated', null, ['disabled' => true])
            ->add('obtainmentFr')
            ->add('obtainmentEn')
            ->add('obtainmentDe')
            ->add('obtainmentEs')
            ->add('obtainmentIt')
            ->add('obtainmentPt')
            ->add('obtainmentJa')
            ->add('obtainmentRu')
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
            ->add('obtainmentJa')
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
