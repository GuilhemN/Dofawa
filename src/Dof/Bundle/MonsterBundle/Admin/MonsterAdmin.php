<?php

namespace Dof\Bundle\MonsterBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MonsterAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('visible')
            ->add('useSummonSlot')
            ->add('useBombSlot')
            ->add('canPlay')
            ->add('canTackle')
            ->add('boss')
            ->add('miniBoss')
            ->add('canBePushed')
            ->add('look')
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
            ->add('release')
            ->add('preliminary')
            ->add('deprecated')
            ->add('path')
            ->add('uploadIndex')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('visible')
            ->add('useSummonSlot')
            ->add('useBombSlot')
            ->add('canPlay')
            ->add('canTackle')
            ->add('boss')
            ->add('miniBoss')
            ->add('canBePushed')
            ->add('look')
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
            ->add('release')
            ->add('preliminary')
            ->add('deprecated')
            ->add('path')
            ->add('uploadIndex')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('id')
            ->add('visible')
            ->add('useSummonSlot')
            ->add('useBombSlot')
            ->add('canPlay')
            ->add('canTackle')
            ->add('boss')
            ->add('miniBoss')
            ->add('canBePushed')
            ->add('look')
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
            ->add('release')
            ->add('preliminary')
            ->add('deprecated')
            ->add('path')
            ->add('uploadIndex')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('visible')
            ->add('useSummonSlot')
            ->add('useBombSlot')
            ->add('canPlay')
            ->add('canTackle')
            ->add('boss')
            ->add('miniBoss')
            ->add('canBePushed')
            ->add('look')
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
            ->add('release')
            ->add('preliminary')
            ->add('deprecated')
            ->add('path')
            ->add('uploadIndex')
        ;
    }
}
