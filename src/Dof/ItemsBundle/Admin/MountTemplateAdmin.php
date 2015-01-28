<?php

namespace Dof\ItemsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MountTemplateAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('nameFr')
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
            ->add('nameFr', null, ['disabled' => true])
            ->add('gestationDuration', null, ['required' => false])
            ->add('minVitality', null, ['required' => false])
            ->add('maxVitality', null, ['required' => false])
            ->add('minStrength', null, ['required' => false])
            ->add('maxStrength', null, ['required' => false])
            ->add('minIntelligence', null, ['required' => false])
            ->add('maxIntelligence', null, ['required' => false])
            ->add('minChance', null, ['required' => false])
            ->add('maxChance', null, ['required' => false])
            ->add('minAgility', null, ['required' => false])
            ->add('maxAgility', null, ['required' => false])
            ->add('minWisdom', null, ['required' => false])
            ->add('maxWisdom', null, ['required' => false])
            ->add('minPower', null, ['required' => false])
            ->add('maxPower', null, ['required' => false])
            ->add('minCriticalHits', null, ['required' => false])
            ->add('maxCriticalHits', null, ['required' => false])
            ->add('minAp', null, ['required' => false])
            ->add('maxAp', null, ['required' => false])
            ->add('minMp', null, ['required' => false])
            ->add('maxMp', null, ['required' => false])
            ->add('minRange', null, ['required' => false])
            ->add('maxRange', null, ['required' => false])
            ->add('minSummons', null, ['required' => false])
            ->add('maxSummons', null, ['required' => false])
            ->add('minDamage', null, ['required' => false])
            ->add('maxDamage', null, ['required' => false])
            ->add('minNeutralDamage', null, ['required' => false])
            ->add('maxNeutralDamage', null, ['required' => false])
            ->add('minEarthDamage', null, ['required' => false])
            ->add('maxEarthDamage', null, ['required' => false])
            ->add('minFireDamage', null, ['required' => false])
            ->add('maxFireDamage', null, ['required' => false])
            ->add('minWaterDamage', null, ['required' => false])
            ->add('maxWaterDamage', null, ['required' => false])
            ->add('minAirDamage', null, ['required' => false])
            ->add('maxAirDamage', null, ['required' => false])
            ->add('minHeals', null, ['required' => false])
            ->add('maxHeals', null, ['required' => false])
            ->add('minProspecting', null, ['required' => false])
            ->add('maxProspecting', null, ['required' => false])
            ->add('minInitiative', null, ['required' => false])
            ->add('maxInitiative', null, ['required' => false])
            ->add('minReflectedDamage', null, ['required' => false])
            ->add('maxReflectedDamage', null, ['required' => false])
            ->add('minPercentNeutralResistance', null, ['required' => false])
            ->add('maxPercentNeutralResistance', null, ['required' => false])
            ->add('minPercentEarthResistance', null, ['required' => false])
            ->add('maxPercentEarthResistance', null, ['required' => false])
            ->add('minPercentFireResistance', null, ['required' => false])
            ->add('maxPercentFireResistance', null, ['required' => false])
            ->add('minPercentWaterResistance', null, ['required' => false])
            ->add('maxPercentWaterResistance', null, ['required' => false])
            ->add('minPercentAirResistance', null, ['required' => false])
            ->add('maxPercentAirResistance', null, ['required' => false])
            ->add('minNeutralResistance', null, ['required' => false])
            ->add('maxNeutralResistance', null, ['required' => false])
            ->add('minEarthResistance', null, ['required' => false])
            ->add('maxEarthResistance', null, ['required' => false])
            ->add('minFireResistance', null, ['required' => false])
            ->add('maxFireResistance', null, ['required' => false])
            ->add('minWaterResistance', null, ['required' => false])
            ->add('maxWaterResistance', null, ['required' => false])
            ->add('minAirResistance', null, ['required' => false])
            ->add('maxAirResistance', null, ['required' => false])
            ->add('minPercentNeutralResistanceInPvp', null, ['required' => false])
            ->add('maxPercentNeutralResistanceInPvp', null, ['required' => false])
            ->add('minPercentEarthResistanceInPvp', null, ['required' => false])
            ->add('maxPercentEarthResistanceInPvp', null, ['required' => false])
            ->add('minPercentFireResistanceInPvp', null, ['required' => false])
            ->add('maxPercentFireResistanceInPvp', null, ['required' => false])
            ->add('minPercentWaterResistanceInPvp', null, ['required' => false])
            ->add('maxPercentWaterResistanceInPvp', null, ['required' => false])
            ->add('minPercentAirResistanceInPvp', null, ['required' => false])
            ->add('maxPercentAirResistanceInPvp', null, ['required' => false])
            ->add('minNeutralResistanceInPvp', null, ['required' => false])
            ->add('maxNeutralResistanceInPvp', null, ['required' => false])
            ->add('minEarthResistanceInPvp', null, ['required' => false])
            ->add('maxEarthResistanceInPvp', null, ['required' => false])
            ->add('minFireResistanceInPvp', null, ['required' => false])
            ->add('maxFireResistanceInPvp', null, ['required' => false])
            ->add('minWaterResistanceInPvp', null, ['required' => false])
            ->add('maxWaterResistanceInPvp', null, ['required' => false])
            ->add('minAirResistanceInPvp', null, ['required' => false])
            ->add('maxAirResistanceInPvp', null, ['required' => false])
            ->add('minLock', null, ['required' => false])
            ->add('maxLock', null, ['required' => false])
            ->add('minDodge', null, ['required' => false])
            ->add('maxDodge', null, ['required' => false])
            ->add('minApReduction', null, ['required' => false])
            ->add('maxApReduction', null, ['required' => false])
            ->add('minMpReduction', null, ['required' => false])
            ->add('maxMpReduction', null, ['required' => false])
            ->add('minApLossResistance', null, ['required' => false])
            ->add('maxApLossResistance', null, ['required' => false])
            ->add('minMpLossResistance', null, ['required' => false])
            ->add('maxMpLossResistance', null, ['required' => false])
            ->add('minCriticalDamage', null, ['required' => false])
            ->add('maxCriticalDamage', null, ['required' => false])
            ->add('minCriticalResistance', null, ['required' => false])
            ->add('maxCriticalResistance', null, ['required' => false])
            ->add('minPushbackDamage', null, ['required' => false])
            ->add('maxPushbackDamage', null, ['required' => false])
            ->add('minPushbackResistance', null, ['required' => false])
            ->add('maxPushbackResistance', null, ['required' => false])
            ->add('minTrapPower', null, ['required' => false])
            ->add('maxTrapPower', null, ['required' => false])
            ->add('minTrapDamage', null, ['required' => false])
            ->add('maxTrapDamage', null, ['required' => false])
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
            ->add('iconId')
            ->add('dominantColor')
            ->add('criteria')
            ->add('level')
            ->add('weight')
            ->add('tradeable')
            ->add('npcPrice')
            ->add('visible')
            ->add('sticky')
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
            ->add('path')
            ->add('uploadIndex')
            ->add('enhanceable')
            ->add('powerRate')
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
            ->add('primaryBonus')
            ->add('bone')
            ->add('colorizationType')
            ->add('favoriteAreaBonus')
            ->add('size')
            ->add('skins')
            ->add('colors')
        ;
    }
}
