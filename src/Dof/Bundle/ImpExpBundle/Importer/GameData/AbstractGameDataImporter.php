<?php

namespace Dof\Bundle\ImpExpBundle\Importer\GameData;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;
use XN\DependencyInjection\ServiceArray;
use Dof\Bundle\ImpExpBundle\ImporterInterface;
use Dof\Bundle\ImpExpBundle\ImporterFlags;

abstract class AbstractGameDataImporter implements ImporterInterface
{
    /**
     * @var GameDataProvider
     */
    protected $gd;

    /**
     * @var ObjectManager
     */
    protected $dm;

    public function __construct(ServiceArray $sa)
    {
        $this->gd = $sa[0];
        $this->dm = $sa[1];
    }

    public function import($dataSet, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        switch ($dataSet) {
            case static::CURRENT_DATA_SET:
                $db = $this->gd->getCurrentReleaseDatabase();
                if ($db === null) {
                    return;
                }
                $beta = false;
                $release = $this->gd->getCurrentReleaseNumber();
                $locales = $this->gd->getCurrentReleaseLocales();
                break;
            case static::BETA_DATA_SET:
                $db = $this->gd->getBetaReleaseDatabase();
                if ($db === null) {
                    return;
                }
                $beta = true;
                $release = $this->gd->getBetaReleaseNumber();
                $locales = $this->gd->getBetaReleaseLocales();
                break;
            default:
                return;
        }
        $conn = $this->gd->getConnection();
        $this->dm->clear();
        $this->doImport($conn, $beta, $release, $db, $locales, $flags, $output, $progress);
        if (($flags & ImporterFlags::DRY_RUN) == 0) {
            $this->dm->flush();
        }
        $this->dm->clear();
    }
    abstract protected function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null);

    protected function generateD2ISelects($col, array $locales, $infix = '', $alias = null)
    {
        if ($alias === null) {
            $alias = $col;
        }

        return implode('', array_map(function ($locale) use ($col, $infix, $alias) {
            return ', i'.$infix.$col.$locale.'.value '.$alias.ucfirst($locale);
        }, $locales));
    }

    protected function generateD2IJoins($col, $db, array $locales, $infix = '')
    {
        return implode('', array_map(function ($locale) use ($col, $db, $infix) {
            return ' LEFT JOIN '.$db.'.D2I_'.$locale.' i'.$infix.$col.$locale.' ON o'.$infix.'.'.$col.'Id = i'.$infix.$col.$locale.'.id';
        }, $locales));
    }

    protected function copyI18NProperty($entity, $field, $row, $alias)
    {
        $repo = $this->dm->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        if (isset($row[$alias.'Fr'])) {
            $repo->translate($entity, $field, 'fr', $row[$alias.'Fr']);
        }
        if (isset($row[$alias.'En'])) {
            $repo->translate($entity, $field, 'en', $row[$alias.'En']);
        }
        if (isset($row[$alias.'De'])) {
            $repo->translate($entity, $field, 'de', $row[$alias.'De']);
        }
        if (isset($row[$alias.'Es'])) {
            $repo->translate($entity, $field, 'es', $row[$alias.'Es']);
        }
        if (isset($row[$alias.'It'])) {
            $repo->translate($entity, $field, 'it', $row[$alias.'It']);
        }
        if (isset($row[$alias.'Pt'])) {
            $repo->translate($entity, $field, 'pt', $row[$alias.'Pt']);
        }
        if (isset($row[$alias.'Ja'])) {
            $repo->translate($entity, $field, 'ja', $row[$alias.'Ja']);
        }
        if (isset($row[$alias.'Ru'])) {
            $repo->translate($entity, $field, 'ru', $row[$alias.'Ru']);
        }
    }
}
