<?php

namespace Dof\ItemsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class SkinnedEquipmentTemplateAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('level')
            ->add('visible')
            ->add('slug')
            ->add('release')
            ->add('preliminary')
            ->add('deprecated')
            ->add('nameFr')
            ->add('descriptionFr')
            ->add('enhanceable')
            ->add('skin')
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
            ->add('skin')
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
            ->add('skin')
            ->add('obtainmentFr')
            ->add('obtainmentEn')
            ->add('obtainmentDe')
            ->add('obtainmentEs')
            ->add('obtainmentIt')
            ->add('obtainmentPt')
            ->add('obtainmentJa')
            ->add('obtainmentRu')
            ->add('level', null, ['disabled' => true])
            ->add('visible', null, ['required' => false])
            ->add('release', null, ['disabled' => true])
            ->add('preliminary', null, ['disabled' => true])
            ->add('deprecated', null, ['disabled' => true])
            ->add('nameFr', null, ['disabled' => true])
            ->add('descriptionFr', null, ['disabled' => true])
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
            ->add('enhanceable')
            ->add('minVitality')
            ->add('maxVitality')
            ->add('minStrength')
            ->add('maxStrength')
            ->add('minIntelligence')
            ->add('maxIntelligence')
            ->add('minChance')
            ->add('maxChance')
            ->add('minAgility')
            ->add('maxAgility')
            ->add('minWisdom')
            ->add('maxWisdom')
            ->add('minPower')
            ->add('maxPower')
            ->add('minCriticalHits')
            ->add('maxCriticalHits')
            ->add('minAp')
            ->add('maxAp')
            ->add('minMp')
            ->add('maxMp')
            ->add('minRange')
            ->add('maxRange')
            ->add('minSummons')
            ->add('maxSummons')
            ->add('minDamage')
            ->add('maxDamage')
            ->add('minNeutralDamage')
            ->add('maxNeutralDamage')
            ->add('minEarthDamage')
            ->add('maxEarthDamage')
            ->add('minFireDamage')
            ->add('maxFireDamage')
            ->add('minWaterDamage')
            ->add('maxWaterDamage')
            ->add('minAirDamage')
            ->add('maxAirDamage')
            ->add('minHeals')
            ->add('maxHeals')
            ->add('minProspecting')
            ->add('maxProspecting')
            ->add('minInitiative')
            ->add('maxInitiative')
            ->add('minReflectedDamage')
            ->add('maxReflectedDamage')
            ->add('minPercentNeutralResistance')
            ->add('maxPercentNeutralResistance')
            ->add('minPercentEarthResistance')
            ->add('maxPercentEarthResistance')
            ->add('minPercentFireResistance')
            ->add('maxPercentFireResistance')
            ->add('minPercentWaterResistance')
            ->add('maxPercentWaterResistance')
            ->add('minPercentAirResistance')
            ->add('maxPercentAirResistance')
            ->add('minNeutralResistance')
            ->add('maxNeutralResistance')
            ->add('minEarthResistance')
            ->add('maxEarthResistance')
            ->add('minFireResistance')
            ->add('maxFireResistance')
            ->add('minWaterResistance')
            ->add('maxWaterResistance')
            ->add('minAirResistance')
            ->add('maxAirResistance')
            ->add('minPercentNeutralResistanceInPvp')
            ->add('maxPercentNeutralResistanceInPvp')
            ->add('minPercentEarthResistanceInPvp')
            ->add('maxPercentEarthResistanceInPvp')
            ->add('minPercentFireResistanceInPvp')
            ->add('maxPercentFireResistanceInPvp')
            ->add('minPercentWaterResistanceInPvp')
            ->add('maxPercentWaterResistanceInPvp')
            ->add('minPercentAirResistanceInPvp')
            ->add('maxPercentAirResistanceInPvp')
            ->add('minNeutralResistanceInPvp')
            ->add('maxNeutralResistanceInPvp')
            ->add('minEarthResistanceInPvp')
            ->add('maxEarthResistanceInPvp')
            ->add('minFireResistanceInPvp')
            ->add('maxFireResistanceInPvp')
            ->add('minWaterResistanceInPvp')
            ->add('maxWaterResistanceInPvp')
            ->add('minAirResistanceInPvp')
            ->add('maxAirResistanceInPvp')
            ->add('minLock')
            ->add('maxLock')
            ->add('minDodge')
            ->add('maxDodge')
            ->add('minApReduction')
            ->add('maxApReduction')
            ->add('minMpReduction')
            ->add('maxMpReduction')
            ->add('minApLossResistance')
            ->add('maxApLossResistance')
            ->add('minMpLossResistance')
            ->add('maxMpLossResistance')
            ->add('minCriticalDamage')
            ->add('maxCriticalDamage')
            ->add('minCriticalResistance')
            ->add('maxCriticalResistance')
            ->add('minPushbackDamage')
            ->add('maxPushbackDamage')
            ->add('minPushbackResistance')
            ->add('maxPushbackResistance')
            ->add('minTrapPower')
            ->add('maxTrapPower')
            ->add('minTrapDamage')
            ->add('maxTrapDamage')
            ->add('skin')
        ;
    }
}
