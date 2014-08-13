<?php

namespace XN\L10n;

use Doctrine\ORM\Mapping as ORM;

trait LocalizedDescriptionTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="description_fr", type="text")
     */
    private $descriptionFr;

    /**
     * @var string
     *
     * @ORM\Column(name="description_en", type="text", nullable=true)
     */
    private $descriptionEn;

    /**
     * @var string
     *
     * @ORM\Column(name="description_de", type="text", nullable=true)
     */
    private $descriptionDe;

    /**
     * @var string
     *
     * @ORM\Column(name="description_es", type="text", nullable=true)
     */
    private $descriptionEs;

    /**
     * @var string
     *
     * @ORM\Column(name="description_it", type="text", nullable=true)
     */
    private $descriptionIt;

    /**
     * @var string
     *
     * @ORM\Column(name="description_pt", type="text", nullable=true)
     */
    private $descriptionPt;

    /**
     * @var string
     *
     * @ORM\Column(name="description_jp", type="text", nullable=true)
     */
    private $descriptionJp;

    /**
     * @var string
     *
     * @ORM\Column(name="description_ru", type="text", nullable=true)
     */
    private $descriptionRu;

    /**
     * Set descriptionFr
     *
     * @param string $descriptionFr
     * @return object
     */
    public function setDescriptionFr($descriptionFr)
    {
        $this->descriptionFr = $descriptionFr;

        return $this;
    }

    /**
     * Get descriptionFr
     *
     * @return string
     */
    public function getDescriptionFr()
    {
        return $this->descriptionFr;
    }

    /**
     * Set descriptionEn
     *
     * @param string $descriptionEn
     * @return object
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->descriptionEn = $descriptionEn;

        return $this;
    }

    /**
     * Get descriptionEn
     *
     * @return string
     */
    public function getDescriptionEn()
    {
        return $this->descriptionEn;
    }

    /**
     * Set descriptionDe
     *
     * @param string $descriptionDe
     * @return object
     */
    public function setDescriptionDe($descriptionDe)
    {
        $this->descriptionDe = $descriptionDe;

        return $this;
    }

    /**
     * Get descriptionDe
     *
     * @return string
     */
    public function getDescriptionDe()
    {
        return $this->descriptionDe;
    }

    /**
     * Set descriptionEs
     *
     * @param string $descriptionEs
     * @return object
     */
    public function setDescriptionEs($descriptionEs)
    {
        $this->descriptionEs = $descriptionEs;

        return $this;
    }

    /**
     * Get descriptionEs
     *
     * @return string
     */
    public function getDescriptionEs()
    {
        return $this->descriptionEs;
    }

    /**
     * Set descriptionIt
     *
     * @param string $descriptionIt
     * @return object
     */
    public function setDescriptionIt($descriptionIt)
    {
        $this->descriptionIt = $descriptionIt;

        return $this;
    }

    /**
     * Get descriptionIt
     *
     * @return string
     */
    public function getDescriptionIt()
    {
        return $this->descriptionIt;
    }

    /**
     * Set descriptionPt
     *
     * @param string $descriptionPt
     * @return object
     */
    public function setDescriptionPt($descriptionPt)
    {
        $this->descriptionPt = $descriptionPt;

        return $this;
    }

    /**
     * Get descriptionPt
     *
     * @return string
     */
    public function getDescriptionPt()
    {
        return $this->descriptionPt;
    }

    /**
     * Set descriptionJp
     *
     * @param string $descriptionJp
     * @return object
     */
    public function setDescriptionJp($descriptionJp)
    {
        $this->descriptionJp = $descriptionJp;

        return $this;
    }

    /**
     * Get descriptionJp
     *
     * @return string
     */
    public function getDescriptionJp()
    {
        return $this->descriptionJp;
    }

    /**
     * Set descriptionRu
     *
     * @param string $descriptionRu
     * @return object
     */
    public function setDescriptionRu($descriptionRu)
    {
        $this->descriptionRu = $descriptionRu;

        return $this;
    }

    /**
     * Get descriptionRu
     *
     * @return string
     */
    public function getDescriptionRu()
    {
        return $this->descriptionRu;
    }

    /**
     * Set description
     *
     * @param string $description
     * @param string $locale
     * @return object
     */
    public function setDescription($description, $locale = 'fr')
    {
        switch ($locale) {
            case 'fr': $this->descriptionFr = $description; break;
            case 'en': $this->descriptionEn = $description; break;
            case 'de': $this->descriptionDe = $description; break;
            case 'es': $this->descriptionEs = $description; break;
            case 'it': $this->descriptionIt = $description; break;
            case 'pt': $this->descriptionPt = $description; break;
            case 'jp': $this->descriptionJp = $description; break;
            case 'ru': $this->descriptionRu = $description; break;
        }
        return $this;
    }

    /**
     * Get description
     *
     * @param string|array $locale
     * @return string
     */
    public function getDescription($locale = 'fr')
    {
        if (is_array($locale)) {
            foreach ($locale as $loc) {
                $description = $this->getDescription($loc);
                if ($description !== null)
                    return $description;
            }
            return null;
        }
        switch ($locale) {
            case 'fr': return $this->descriptionFr;
            case 'en': return $this->descriptionEn;
            case 'de': return $this->descriptionDe;
            case 'es': return $this->descriptionEs;
            case 'it': return $this->descriptionIt;
            case 'pt': return $this->descriptionPt;
            case 'jp': return $this->descriptionJp;
            case 'ru': return $this->descriptionRu;
            default: return null;
        }
    }
}
