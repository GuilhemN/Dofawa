<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterFlags;

use Dof\CharactersBundle\Entity\SpellRank;

class SpellRankImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'spell_ranks';
    const BETA_DATA_SET = 'beta_spell_ranks';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
        $this->dm->createQuery('UPDATE DofCharactersBundle:Spell s SET s.deprecated = true')->execute();

        $stmt = $conn->query('SELECT o.* FROM ' . $db . '.D2O_Spell o');
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $repo = $this->dm->getRepository('DofCharactersBundle:SpellRank');
        $spellRepo = $this->dm->getRepository('DofCharactersBundle:Spell');
        $rowsProcessed = 0;
        if ($output && $progress)
        $progress->start($output, count($all));
        foreach ($all as $row) {
            $tpl = $repo->find($row['id']);
            $spell = $spellRepo->find($row['spellId']);
            if ($tpl === null) {
                $tpl = new SpellRank();
                $tpl->setId($row['id']);
            }
            if ($spell->isPreliminary() == $beta) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease())
                $tpl->setSpell($spell);
                $tpl->setRank($row['grade']);

                $tpl->setNeedsFreeCell($row['needFreeCell']);
                $tpl->setNeedsTakenCell($row['needTakenCell']);
                $tpl->setNeedsFreeTrapCell($row['needFreeTrapCell']);
                $tpl->setModifiableCastRange($row['rangeCanBeBoosted']);
                $tpl->setMaxEffectStack($row['maxStack']);
                $tpl->setMaxCastsPerTarget($row['maxCastPerTarget']);
                $tpl->setCooldown($row['minCastInterval']);
                $tpl->setInitialCooldown($row['initialCooldown']);
                $tpl->setGlobalCooldown($row['globalCooldown']);
                $tpl->setObtainmentLevel($row['minPlayerLevel']);

                $tpl->setCriticalHitDenominator($row['criticalHitProbability']);
                $tpl->setMaxCastsPerTurn($row['maxCastPerTurn']);
                $tpl->setApCost($row['apCost']);
                $tpl->setMinCastRange($row['minRange']);
                $tpl->setMaxCastRange($row['range']);
                $tpl->setSightCast($row['castTestLos']);
                $tpl->setLineCast($row['castInLine']);
                $tpl->setDiagonalCast($row['castInDiagonal']);

                $this->dm->persist($tpl);
            }
            ++$rowsProcessed;
            if (($rowsProcessed % 300) == 0) {
                $this->dm->flush();
                $this->dm->clear();
                if ($output && $progress)
                $progress->advance(300);
            }
        }
        if ($output && $progress)
        $progress->finish();

    }
}
