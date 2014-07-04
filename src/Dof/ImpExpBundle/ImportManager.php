<?php

namespace Dof\ImpExpBundle;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

class ImportManager
{
    private $importers;
    private $withRequirements;
    private $runImporters;

    public function __construct()
    {
        $this->importers = [ ];
        $this->withRequirements = true;
        $this->runImporters = true;
    }

    public function setWithRequirements($withRequirements)
    {
        $this->withRequirements = $withRequirements;
        return $this;
    }
    public function getWithRequirements()
    {
        return $this->withRequirements;
    }

    public function setRunImporters($runImporters)
    {
        $this->runImporters = $runImporters;
        return $this;
    }
    public function getRunImporters()
    {
        return $this->runImporters;
    }

    public function registerDataSet($dataSet, ImporterInterface $importer, array $requirements)
    {
        if (isset($this->importers[$dataSet]))
            throw new \Exception("Importer for data set \"" . $dataSet . "\" already registered");
        sort($requirements);
        $this->importers[$dataSet] = [
            'importer' => $importer,
            'requirements' => $requirements,
            'checking' => false,
            'imported' => false
        ];
        return $this;
    }

    public function getDataSets()
    {
        $dataSets = array_keys($this->importers);
        sort($dataSets);
        return $dataSets;
    }
    public function getDataSetImporter($dataSet)
    {
        if (!isset($this->importers[$dataSet]))
            throw new \Exception("Unknown data set \"" . $dataSet . "\"");
        return $this->importers[$dataSet]['importer'];
    }
    public function getDataSetRequirements($dataSet)
    {
        if (!isset($this->importers[$dataSet]))
            throw new \Exception("Unknown data set \"" . $dataSet . "\"");
        return $this->importers[$dataSet]['requirements'];
    }

    public function import($dataSet, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $this->checkImport($dataSet);
        $this->realImport($dataSet, $flags, $output, $progress);
        return $this;
    }

    private function checkImport($dataSet)
    {
        if (!isset($this->importers[$dataSet]))
            throw new \Exception("Unknown data set \"" . $dataSet . "\"");
        $importer =& $this->importers[$dataSet];
        if ($importer['imported'])
            return;
        if ($importer['checking'])
            throw new \Exception("Circular reference involving data set \"" . $dataSet . "\"");
        $importer['checking'] = true;
        try {
            foreach ($importer['requirements'] as $requirement)
                $this->checkImport($requirement);
        } catch (\Exception $e) {
            $importer['checking'] = false;
            throw $e;
        }
        $importer['checking'] = false;
        return $this;
    }
    private function realImport($dataSet, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        if (!isset($this->importers[$dataSet]))
            throw new \Exception("Unknown data set \"" . $dataSet . "\"");
        $importer =& $this->importers[$dataSet];
        if ($importer['imported'])
            return;
        foreach ($importer['requirements'] as $requirement)
            $this->realImport($requirement, $flags, $output, $progress);
        if ($output !== null && $output->getVerbosity() >= OutputInterface::VERBOSITY_NORMAL)
            $output->writeln('Importing data set <comment>' . $dataSet . '</comment> ...');
        if ($this->runImporters)
            $importer['importer']->import($dataSet, $flags, $output, $progress);
        $importer['imported'] = true;
        return $this;
    }

    public function markAsImported($dataSet)
    {
        if (!isset($this->importers[$dataSet]))
            throw new \Exception("Unknown data set \"" . $dataSet . "\"");
        $this->importers[$dataSet]['imported'] = true;
        return $this;
    }

    public function reset()
    {
        foreach ($this->importers as &$importer)
            $importer['imported'] = false;
        return $this;
    }
}
