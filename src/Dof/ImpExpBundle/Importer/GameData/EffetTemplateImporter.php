<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\ImporterFlags;

use Dof\CharactersBundle\Entity\EffectTemplate;

use Dof\ItemsBundle\Element;

class EffectTemplateImporter extends AbstractGameDataImporter
{
    const CURRENT_DATA_SET = 'effect_templates';
    const BETA_DATA_SET = 'beta_effect_templates';

    protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $write = ($flags & ImporterFlags::DRY_RUN) == 0;
        if (!$beta && $write)
            $this->dm->createQuery('UPDATE DofCharactersBundle:EffectTemplate s SET s.deprecated = true')->execute();

        $stmt = $conn->query('SELECT o.*' .
        $this->generateD2ISelects('description', $locales) .
        ' FROM ' . $db . '.D2O_Effect o' .
        $this->generateD2IJoins('description', $db, $locales));
        $all = $stmt->fetchAll();
        $stmt->closeCursor();

        $repo = $this->dm->getRepository('DofCharactersBundle:EffectTemplate');
        $rowsProcessed = 0;
        if ($output && $progress)
            $progress->start($output, count($all));
        foreach ($all as $row) {
            $tpl = $repo->find($row['id']);
            if ($tpl === null) {
                $tpl = new EffectTemplate();
                $tpl->setDeprecated(true);
                $tpl->setId($row['id']);
            }
            if ($tpl->isDeprecated()) {
                $tpl->setDeprecated(false);
                if (!$tpl->getRelease())
                    $tpl->setRelease($release);
                $tpl->setPreliminary($beta);
                $this->copyI18NProperty($tpl, 'setDescription', $row, 'description');
                $tpl->setCharacteristic(($row['characteristic'] === 'null') ? null : $row['characteristic']);
                $tpl->setElement(($row['elementId'] == -1) ? null : $row['elementId']);
                if ($row['operator'] == '+')
                    $tpl->setOperator(1);
                elseif ($row['operator'] == '-')
                    $tpl->setOperator(-1);
                elseif ($row['operator'] == '/')
                    $tpl->setOperator(0);
                else
                    $tpl->setOperator(null);
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
