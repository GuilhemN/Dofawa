<?php

namespace Dof\ImpExpBundle\Importer\GameData;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use Dof\ImpExpBundle\GameDataProvider;
use Dof\ImpExpBundle\ImporterInterface;

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

    public function __construct(GameDataProvider $gd, ObjectManager $dm)
    {
        $this->gd = $gd;
        $this->dm = $dm;
    }

    public function import($dataSet, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        switch ($dataSet) {
            case static::CURRENT_DATA_SET:
                $beta = false;
                $release = $this->gd->getCurrentReleaseNumber();
                $db = $this->gd->getCurrentReleaseDatabase();
                $locales = $this->gd->getCurrentReleaseLocales();
                break;
            case static::BETA_DATA_SET:
                $beta = true;
                $release = $this->gd->getBetaReleaseNumber();
                $db = $this->gd->getBetaReleaseDatabase();
                $locales = $this->gd->getBetaReleaseLocales();
                break;
            default:
                return;
        }
        $conn = $this->gd->getConnection();
        $this->doImport($conn, $beta, $release, $db, $locales, $flags, $output, $progress);
    }
    protected abstract function doImport($conn, $beta, $release, $db, array $locales, $flags, OutputInterface $output = null, ProgressHelper $progress = null);

    protected function generateD2ISelects($col, array $locales, $infix = '', $alias = null)
    {
        if ($alias === null)
            $alias = $col;
        return implode('', array_map(function ($locale) use ($col, $infix, $alias) {
            return ', i' . $infix . $col . $locale . '.value ' . $alias . ucfirst($locale);
        }, $locales));
    }

    protected function generateD2IJoins($col, $db, array $locales, $infix = '')
    {
        return implode('', array_map(function ($locale) use ($col, $db, $infix) {
            return ' LEFT JOIN ' . $db . '.D2I_' . $locale . ' i' . $infix . $col . $locale . ' ON o' . $infix . '.' . $col . 'Id = i' . $infix . $col . $locale . '.id';
        }, $locales));
    }

    protected function copyI18NProperty($entity, $setter, $row, $alias)
    {
        if (isset($row[$alias . 'Fr']))
            $entity->$setter($row[$alias . 'Fr'], 'fr');
        if (isset($row[$alias . 'En']))
            $entity->$setter($row[$alias . 'En'], 'en');
        if (isset($row[$alias . 'De']))
            $entity->$setter($row[$alias . 'De'], 'de');
        if (isset($row[$alias . 'Es']))
            $entity->$setter($row[$alias . 'Es'], 'es');
        if (isset($row[$alias . 'It']))
            $entity->$setter($row[$alias . 'It'], 'it');
        if (isset($row[$alias . 'Pt']))
            $entity->$setter($row[$alias . 'Pt'], 'pt');
        if (isset($row[$alias . 'Jp']))
            $entity->$setter($row[$alias . 'Jp'], 'jp');
        if (isset($row[$alias . 'Ru']))
            $entity->$setter($row[$alias . 'Ru'], 'ru');
    }
}
