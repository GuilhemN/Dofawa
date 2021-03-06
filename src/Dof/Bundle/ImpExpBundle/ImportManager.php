<?php

namespace Dof\Bundle\ImpExpBundle;

use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

class ImportManager
{
    private $importers;
    private $withRequirements;
    private $runImporters;
    private $fork;

    public function __construct()
    {
        $this->importers = [];
        $this->withRequirements = true;
        $this->runImporters = true;
        $this->fork = function_exists('pcntl_fork') && function_exists('pcntl_waitpid');
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

    public function setFork($fork)
    {
        $this->fork = $fork && function_exists('pcntl_fork') && function_exists('pcntl_waitpid');

        return $this;
    }

    public function getFork()
    {
        return $this->fork;
    }

    public function registerDataSet($dataSet, ImporterInterface $importer, array $requirements, array $groups)
    {
        if (isset($this->importers[$dataSet])) {
            throw new \Exception('Importer for data set "'.$dataSet.'" already registered');
        }
        sort($requirements);
        $this->importers[$dataSet] = [
            'importer' => $importer,
            'requirements' => $requirements,
            'groups' => $groups,
            'checking' => false,
            'imported' => false,
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
        if (!isset($this->importers[$dataSet])) {
            throw new \Exception('Unknown data set "'.$dataSet.'"');
        }

        return $this->importers[$dataSet]['importer'];
    }

    public function getDataSetRequirements($dataSet)
    {
        if (!isset($this->importers[$dataSet])) {
            throw new \Exception('Unknown data set "'.$dataSet.'"');
        }

        return $this->importers[$dataSet]['requirements'];
    }

    public function getDataSetGroups($dataSet)
    {
        if (!isset($this->importers[$dataSet])) {
            throw new \Exception('Unknown data set "'.$dataSet.'"');
        }

        return $this->importers[$dataSet]['groups'];
    }

    public function import($dataSet, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        $this->checkImport($dataSet);
        $this->realImport($dataSet, $flags, $output, $progress);

        return $this;
    }

    private function checkImport($dataSet)
    {
        if (!isset($this->importers[$dataSet])) {
            throw new \Exception('Unknown data set "'.$dataSet.'"');
        }
        $importer = &$this->importers[$dataSet];
        if ($importer['imported']) {
            return;
        }
        if ($importer['checking']) {
            throw new \Exception('Circular reference involving data set "'.$dataSet.'"');
        }
        $importer['checking'] = true;
        try {
            if ($this->withRequirements) {
                foreach ($importer['requirements'] as $requirement) {
                    $this->checkImport($requirement);
                }
            }
        } catch (\Exception $e) {
            $importer['checking'] = false;
            throw $e;
        }
        $importer['checking'] = false;

        return $this;
    }

    private function realImport($dataSet, $flags, OutputInterface $output = null, ProgressHelper $progress = null)
    {
        if (!isset($this->importers[$dataSet])) {
            throw new \Exception('Unknown data set "'.$dataSet.'"');
        }
        $importer = &$this->importers[$dataSet];
        if ($importer['imported']) {
            return;
        }
        if ($this->withRequirements) {
            foreach ($importer['requirements'] as $requirement) {
                $this->realImport($requirement, $flags, $output, $progress);
            }
        }
        if ($output !== null && $output->getVerbosity() >= OutputInterface::VERBOSITY_NORMAL) {
            $output->writeln('Importing data set <comment>'.$dataSet.'</comment> ...');
        }
        if ($this->runImporters) {
            if ($this->fork) {
                $pid = pcntl_fork();
                if ($pid < 0) {
                    throw new \Exception('Can\'t fork to import the data set');
                } elseif ($pid) {
                    if (pcntl_waitpid($pid, $status) < 0) {
                        throw new \Exception('Can\'t wait for the worker process to finish');
                    } elseif (!pcntl_wifexited($status)) {
                        throw new \Exception('The worker process exited abnormally');
                    } elseif (($status = pcntl_wexitstatus($status))) {
                        throw new \Exception('The worker process failed with status code '.$status);
                    }
                } else {
                    $importer['importer']->import($dataSet, $flags, $output, $progress);
                    exit(0);
                }
            } else {
                $importer['importer']->import($dataSet, $flags, $output, $progress);
            }
        }
        $importer['imported'] = true;

        return $this;
    }

    public function markAsImported($dataSet)
    {
        if (!isset($this->importers[$dataSet])) {
            throw new \Exception('Unknown data set "'.$dataSet.'"');
        }
        $this->importers[$dataSet]['imported'] = true;

        return $this;
    }

    public function markGroupAsImported($group)
    {
        foreach ($this->importers as $dataset => $options) {
            foreach ($options['groups'] as $g) {
                if ($g == $group) {
                    $this->markAsImported($dataset);
                    break;
                }
            }
        }

        return $this;
    }

    public function reset()
    {
        foreach ($this->importers as &$importer) {
            $importer['imported'] = false;
        }

        return $this;
    }
}
