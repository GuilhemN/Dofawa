<?php

namespace Dof\ImpExpBundle;

class GameDataProvider
{
    /**
     * @var object
     */
    private $conn;

    /**
     * @var string
     */
    private $currentReleaseNumber;

    /**
     * @var string
     */
    private $betaReleaseNumber;

    /**
     * @var string
     */
    private $currentReleaseDatabase;

    /**
     * @var string
     */
    private $betaReleaseDatabase;

    /**
     * @var array
     */
    private $currentReleaseLocales;

    /**
     * @var array
     */
    private $betaReleaseLocales;

    public function __construct($conn, $currentReleaseNumber, $betaReleaseNumber, $currentReleaseDatabase, $betaReleaseDatabase)
    {
        $this->conn = $conn;
        $this->currentReleaseNumber = $currentReleaseNumber;
        $this->betaReleaseNumber = $betaReleaseNumber;
        $this->currentReleaseDatabase = $currentReleaseDatabase;
        $this->betaReleaseDatabase = $betaReleaseDatabase;
        $this->currentReleaseLocales = null;
        $this->betaReleaseLocales = null;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function getCurrentReleaseNumber()
    {
        return $this->currentReleaseNumber;
    }

    public function getBetaReleaseNumber()
    {
        return $this->betaReleaseNumber;
    }

    public function getCurrentReleaseDatabase()
    {
        return $this->currentReleaseDatabase;
    }

    public function getBetaReleaseDatabase()
    {
        return $this->betaReleaseDatabase;
    }

    public function getCurrentReleaseLocales()
    {
        if ($this->currentReleaseLocales === null) {
            $this->currentReleaseLocales = [ ];
            try {
                $this->conn->query('DESCRIBE ' . $this->currentReleaseDatabase . '.D2I_fr')->closeCursor();
                $this->currentReleaseLocales[] = 'fr';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->currentReleaseDatabase . '.D2I_en')->closeCursor();
                $this->currentReleaseLocales[] = 'en';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->currentReleaseDatabase . '.D2I_de')->closeCursor();
                $this->currentReleaseLocales[] = 'de';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->currentReleaseDatabase . '.D2I_es')->closeCursor();
                $this->currentReleaseLocales[] = 'es';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->currentReleaseDatabase . '.D2I_it')->closeCursor();
                $this->currentReleaseLocales[] = 'it';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->currentReleaseDatabase . '.D2I_pt')->closeCursor();
                $this->currentReleaseLocales[] = 'pt';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->currentReleaseDatabase . '.D2I_ja')->closeCursor();
                $this->currentReleaseLocales[] = 'ja';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->currentReleaseDatabase . '.D2I_ru')->closeCursor();
                $this->currentReleaseLocales[] = 'ru';
            } catch (\Exception $e) { }
        }
        return $this->currentReleaseLocales;
    }

    public function getBetaReleaseLocales()
    {
        if ($this->betaReleaseLocales === null) {
            $this->betaReleaseLocales = [ ];
            try {
                $this->conn->query('DESCRIBE ' . $this->betaReleaseDatabase . '.D2I_fr')->closeCursor();
                $this->betaReleaseLocales[] = 'fr';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->betaReleaseDatabase . '.D2I_en')->closeCursor();
                $this->betaReleaseLocales[] = 'en';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->betaReleaseDatabase . '.D2I_de')->closeCursor();
                $this->betaReleaseLocales[] = 'de';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->betaReleaseDatabase . '.D2I_es')->closeCursor();
                $this->betaReleaseLocales[] = 'es';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->betaReleaseDatabase . '.D2I_it')->closeCursor();
                $this->betaReleaseLocales[] = 'it';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->betaReleaseDatabase . '.D2I_pt')->closeCursor();
                $this->betaReleaseLocales[] = 'pt';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->betaReleaseDatabase . '.D2I_ja')->closeCursor();
                $this->betaReleaseLocales[] = 'ja';
            } catch (\Exception $e) { }
            try {
                $this->conn->query('DESCRIBE ' . $this->betaReleaseDatabase . '.D2I_ru')->closeCursor();
                $this->betaReleaseLocales[] = 'ru';
            } catch (\Exception $e) { }
        }
        return $this->betaReleaseLocales;
    }
}
