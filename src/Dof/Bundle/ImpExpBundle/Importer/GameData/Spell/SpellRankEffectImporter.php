<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData\Spell;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dof\Bundle\ImpExpBundle\Importer\GameData\AbstractGameDataImporter;
use Dof\Bundle\ImpExpBundle\ImporterFlags;
use XN\Persistence\CollectionSynchronizationHelper;
use Dof\Bundle\CharacterBundle\Entity\SpellRankEffect;

class SpellRankEffectImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'spell_rank_effects';
    const BETA_DATA_SET = 'beta_spell_rank_effects';

    private $paramLoader;

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;

        $this->paramLoader->setEnabled(false);

        $stmt = $conn->query('SELECT o.* FROM '.$db.'.D2O_SpellLevel_effect o
            WHERE o.effectId IN (SELECT id FROM '.$db.'.D2O_Effect)');
        $nEffects = $stmt->fetchAll();
        $stmt->closeCursor();

        $stmt = $conn->query('SELECT o.* FROM '.$db.'.D2O_SpellLevel_criticalEffect o
            WHERE o.effectId IN (SELECT id FROM '.$db.'.D2O_Effect)');
        $cEffects = $stmt->fetchAll();
        $stmt->closeCursor();

        $effects = array();
        foreach ($nEffects as $row) {
            $ranksEffects[$row['id']]['normal'][] = $row + ['critical' => false];
        }
        foreach ($cEffects as $row) {
            $ranksEffects[$row['id']]['critical'][] = $row + ['critical' => true];
        }

        $repo = $this->dm->getRepository('DofCharacterBundle:SpellRankEffect');
        $rankRepo = $this->dm->getRepository('DofCharacterBundle:SpellRank');
        $effectRepo = $this->dm->getRepository('DofCharacterBundle:EffectTemplate');

        $rowsProcessed = 0;
        if ($output && $progress) {
            $progress->start($output, count($ranksEffects));
        }

        foreach ($ranksEffects as $rank => $effects) {
            $rank = $rankRepo->find($rank);
            if ($rank !== null && ($rank->getSpell()->isPreliminary() == $beta)) {
                if (isset($effects['normal'])) {
                    CollectionSynchronizationHelper::synchronize($this->dm, array_values($rank->getNormalEffects()), $effects['normal'], function () use ($rank) {
                    $effect = new SpellRankEffect();
                    $effect->setSpellRank($rank);

                    return $effect;
                }, function ($effect, $row) use ($effectRepo) {
                    $effectTemplate = $effectRepo->find($row['effectId']);
                    $effect->setEffectTemplate($effectTemplate);

                    $effect->setOrder($row['_index1']);
                    $effect->setProbability($row['random'] / 100);
                    $effect->setAreaOfEffect($row['rawZone']);
                    $effect->setTargets(explode(',', $row['targetMask']));
                    $effect->setDuration($row['duration']);
                    $effect->setDelay($row['delay']);
                    $effect->setTriggers(explode('|', $row['triggers']));
                    $effect->setHidden(!$row['visibleInTooltip']);
                    $effect->setCritical($row['critical']);

                    $effect->setParam1($row['diceNum']);
                    $effect->setParam2($row['diceSide']);
                    $effect->setParam3($row['value']);

                });
                }
                if (isset($effects['critical'])) {
                    CollectionSynchronizationHelper::synchronize($this->dm, array_values($rank->getCriticalEffects()), $effects['critical'], function () use ($rank) {
                    $effect = new SpellRankEffect();
                    $effect->setSpellRank($rank);

                    return $effect;
                }, function ($effect, $row) use ($effectRepo) {
                    $effectTemplate = $effectRepo->find($row['effectId']);
                    $effect->setEffectTemplate($effectTemplate);

                    $effect->setOrder($row['_index1']);
                    $effect->setProbability($row['random'] / 100);
                    $effect->setAreaOfEffect($row['rawZone']);
                    $effect->setTargets(explode(',', $row['targetMask']));
                    $effect->setDuration($row['duration']);
                    $effect->setDelay($row['delay']);
                    $effect->setTriggers(explode('|', $row['triggers']));
                    $effect->setHidden(!$row['visibleInTooltip']);
                    $effect->setCritical($row['critical']);

                    $effect->setParam1($row['diceNum']);
                    $effect->setParam2($row['diceSide']);
                    $effect->setParam3($row['value']);

                });
                }
            }

            ++$rowsProcessed;
            if (($rowsProcessed % 300) == 0) {
                if ($output && $progress) {
                    $progress->advance(300);
                }
            }
        }
        if ($output && $progress) {
            $progress->finish();
        }

        $this->paramLoader->setEnabled(true);
    }

    public function setParamLoader($paramLoader)
    {
        $this->paramLoader = $paramLoader;
    }
}
