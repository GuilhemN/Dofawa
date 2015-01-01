<?php

namespace XN\L10n;

use Doctrine\ORM\Mapping as ORM;

trait LocalizedNameTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="name_fr", type="string", length=150, nullable=self::NULLABLE_NAME_FR)
     */
    private $nameFr;

    /**
     * @var string
     *
     * @ORM\Column(name="name_en", type="string", length=150, nullable=true)
     */
    private $nameEn;

    /**
     * @var string
     *
     * @ORM\Column(name="name_de", type="string", length=150, nullable=true)
     */
    private $nameDe;

    /**
     * @var string
     *
     * @ORM\Column(name="name_es", type="string", length=150, nullable=true)
     */
    private $nameEs;

    /**
     * @var string
     *
     * @ORM\Column(name="name_it", type="string", length=150, nullable=true)
     */
    private $nameIt;

    /**
     * @var string
     *
     * @ORM\Column(name="name_pt", type="string", length=150, nullable=true)
     */
    private $namePt;

    /**
     * @var string
     *
     * @ORM\Column(name="name_ja", type="string", length=150, nullable=true)
     */
    private $nameJa;

    /**
     * @var string
     *
     * @ORM\Column(name="name_ru", type="string", length=150, nullable=true)
     */
    private $nameRu;

    /**
     * Set nameFr
     *
     * @param string $nameFr
     * @return object
     */
    public function setNameFr($nameFr)
    {
        $this->nameFr = $nameFr;

        return $this;
    }

    /**
     * Get nameFr
     *
     * @return string
     */
    public function getNameFr()
    {
        return $this->nameFr;
    }

    /**
     * Set nameEn
     *
     * @param string $nameEn
     * @return object
     */
    public function setNameEn($nameEn)
    {
        $this->nameEn = $nameEn;

        return $this;
    }

    /**
     * Get nameEn
     *
     * @return string
     */
    public function getNameEn()
    {
        return $this->nameEn;
    }

    /**
     * Set nameDe
     *
     * @param string $nameDe
     * @return object
     */
    public function setNameDe($nameDe)
    {
        $this->nameDe = $nameDe;

        return $this;
    }

    /**
     * Get nameDe
     *
     * @return string
     */
    public function getNameDe()
    {
        return $this->nameDe;
    }

    /**
     * Set nameEs
     *
     * @param string $nameEs
     * @return object
     */
    public function setNameEs($nameEs)
    {
        $this->nameEs = $nameEs;

        return $this;
    }

    /**
     * Get nameEs
     *
     * @return string
     */
    public function getNameEs()
    {
        return $this->nameEs;
    }

    /**
     * Set nameIt
     *
     * @param string $nameIt
     * @return object
     */
    public function setNameIt($nameIt)
    {
        $this->nameIt = $nameIt;

        return $this;
    }

    /**
     * Get nameIt
     *
     * @return string
     */
    public function getNameIt()
    {
        return $this->nameIt;
    }

    /**
     * Set namePt
     *
     * @param string $namePt
     * @return object
     */
    public function setNamePt($namePt)
    {
        $this->namePt = $namePt;

        return $this;
    }

    /**
     * Get namePt
     *
     * @return string
     */
    public function getNamePt()
    {
        return $this->namePt;
    }

    /**
     * Set nameJa
     *
     * @param string $nameJa
     * @return object
     */
    public function setNameJa($nameJa)
    {
        $this->nameJa = $nameJa;

        return $this;
    }

    /**
     * Get nameJa
     *
     * @return string
     */
    public function getNameJa()
    {
        return $this->nameJa;
    }

    /**
     * Set nameRu
     *
     * @param string $nameRu
     * @return object
     */
    public function setNameRu($nameRu)
    {
        $this->nameRu = $nameRu;

        return $this;
    }

    /**
     * Get nameRu
     *
     * @return string
     */
    public function getNameRu()
    {
        return $this->nameRu;
    }

    /**
     * Set name
     *
     * @param string $name
     * @param string $locale
     * @return object
     */
    public function setName($name, $locale = 'fr')
    {
    	switch ($locale) {
    		case 'fr': $this->nameFr = $name; break;
    		case 'en': $this->nameEn = $name; break;
    		case 'de': $this->nameDe = $name; break;
    		case 'es': $this->nameEs = $name; break;
    		case 'it': $this->nameIt = $name; break;
    		case 'pt': $this->namePt = $name; break;
    		case 'ja': $this->nameJa = $name; break;
    		case 'ru': $this->nameRu = $name; break;
    	}
    	return $this;
    }

    /**
     * Get name
     *
     * @param string|array $locale
     * @return string
     */
    public function getName($locale = 'fr')
    {
    	if (is_array($locale)) {
    		foreach ($locale as $loc) {
    			$name = $this->getName($loc);
    			if ($name !== null)
    				return $name;
    		}
    		return null;
    	}
    	switch ($locale) {
    		case 'fr': return $this->nameFr;
    		case 'en': return $this->nameEn;
    		case 'de': return $this->nameDe;
    		case 'es': return $this->nameEs;
    		case 'it': return $this->nameIt;
    		case 'pt': return $this->namePt;
    		case 'ja': return $this->nameJa;
    		case 'ru': return $this->nameRu;
    		default: return null;
    	}
    }

    public function getNames(){
        return [
            'nameFr' => $this->nameFr,
            'nameEn' => $this->nameEn,
            'nameDe' => $this->nameDe,
            'nameEs' => $this->nameEs,
            'nameIt' => $this->nameIt,
            'namePt' => $this->namePt,
            'nameJa' => $this->nameJa,
            'nameRu' => $this->nameRu
        ];
    }
}
