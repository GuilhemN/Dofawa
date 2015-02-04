<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use Dof\ItemBundle\ReleaseBoundTrait;

/**
 * Title
 *
 * @ORM\Table(name="dof_titles")
 * @ORM\Entity(repositoryClass="Dof\CharactersBundle\Entity\TitleRepository")
 */
class Title implements IdentifiableInterface, TimestampableInterface, SluggableInterface
{
    use TimestampableTrait, SluggableTrait, ReleaseBoundTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * @var integer
     *
     * @ORM\Column(name="categoryId", type="integer")
     */
    private $categoryId;

    /**
    * @var string
    *
    * @ORM\Column(name="name_male_fr", type="string", length=255)
    */
    private $nameMaleFr;

    /**
    * @var string
    *
    * @ORM\Column(name="name_male_en", type="string", length=255, nullable=true)
    */
    private $nameMaleEn;

    /**
    * @var string
    *
    * @ORM\Column(name="name_male_de", type="string", length=255, nullable=true)
    */
    private $nameMaleDe;

    /**
    * @var string
    *
    * @ORM\Column(name="name_male_es", type="string", length=255, nullable=true)
    */
    private $nameMaleEs;

    /**
    * @var string
    *
    * @ORM\Column(name="name_male_it", type="string", length=255, nullable=true)
    */
    private $nameMaleIt;

    /**
    * @var string
    *
    * @ORM\Column(name="name_male_pt", type="string", length=255, nullable=true)
    */
    private $nameMalePt;

    /**
    * @var string
    *
    * @ORM\Column(name="name_male_ja", type="string", length=255, nullable=true)
    */
    private $nameMaleJa;

    /**
    * @var string
    *
    * @ORM\Column(name="nameMale_ru", type="string", length=255, nullable=true)
    */
    private $nameMaleRu;

    /**
    * @var string
    *
    * @ORM\Column(name="name_female_fr", type="string", length=255)
    */
    private $nameFemaleFr;

    /**
    * @var string
    *
    * @ORM\Column(name="name_female_en", type="string", length=255, nullable=true)
    */
    private $nameFemaleEn;

    /**
    * @var string
    *
    * @ORM\Column(name="name_female_de", type="string", length=255, nullable=true)
    */
    private $nameFemaleDe;

    /**
    * @var string
    *
    * @ORM\Column(name="name_female_es", type="string", length=255, nullable=true)
    */
    private $nameFemaleEs;

    /**
    * @var string
    *
    * @ORM\Column(name="name_female_it", type="string", length=255, nullable=true)
    */
    private $nameFemaleIt;

    /**
    * @var string
    *
    * @ORM\Column(name="name_female_pt", type="string", length=255, nullable=true)
    */
    private $nameFemalePt;

    /**
    * @var string
    *
    * @ORM\Column(name="name_female_ja", type="string", length=255, nullable=true)
    */
    private $nameFemaleJa;

    /**
    * @var string
    *
    * @ORM\Column(name="nameFemale_ru", type="string", length=255, nullable=true)
    */
    private $nameFemaleRu;

    /**
    * Set id
    *
    * @param integer $id
    * @return Emoticon
    */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
    * Get id
    *
    * @return integer
    */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return Title
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set categoryId
     *
     * @param integer $categoryId
     * @return Title
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
    * Set nameMaleFr
    *
    * @param string $nameMaleFr
    * @return Emoticon
    */
    public function setNameMaleFr($nameMaleFr)
    {
        $this->nameMaleFr = $nameMaleFr;

        return $this;
    }

    /**
    * Get nameMaleNameFr
    *
    * @return string
    */
    public function getNameMaleFr()
    {
        return $this->nameMaleNameFr;
    }

    /**
    * Set nameMaleNameEn
    *
    * @param string $nameMaleNameEn
    * @return Emoticon
    */
    public function setNameMaleEn($nameMaleNameEn)
    {
        $this->nameMaleNameEn = $nameMaleNameEn;

        return $this;
    }

    /**
    * Get nameMaleNameEn
    *
    * @return string
    */
    public function getNameMaleEn()
    {
        return $this->nameMaleNameEn;
    }

    /**
    * Set nameMaleNameDe
    *
    * @param string $nameMaleNameDe
    * @return Emoticon
    */
    public function setNameMaleDe($nameMaleNameDe)
    {
        $this->nameMaleNameDe = $nameMaleNameDe;

        return $this;
    }

    /**
    * Get nameMaleNameDe
    *
    * @return string
    */
    public function getNameMaleDe()
    {
        return $this->nameMaleDe;
    }

    /**
    * Set nameMaleEs
    *
    * @param string $nameMaleEs
    * @return Emoticon
    */
    public function setNameMaleEs($nameMaleEs)
    {
        $this->nameMaleEs = $nameMaleEs;

        return $this;
    }

    /**
    * Get nameMaleEs
    *
    * @return string
    */
    public function getNameMaleEs()
    {
        return $this->nameMaleEs;
    }

    /**
    * Set nameMaleIt
    *
    * @param string $nameMaleIt
    * @return Emoticon
    */
    public function setNameMaleIt($nameMaleIt)
    {
        $this->nameMaleIt = $nameMaleIt;

        return $this;
    }

    /**
    * Get nameMaleIt
    *
    * @return string
    */
    public function getNameMaleIt()
    {
        return $this->nameMaleIt;
    }

    /**
    * Set nameMalePt
    *
    * @param string $nameMalePt
    * @return Emoticon
    */
    public function setNameMalePt($nameMalePt)
    {
        $this->nameMalePt = $nameMalePt;

        return $this;
    }

    /**
    * Get longNamePt
    *
    * @return string
    */
    public function getNameMalePt()
    {
        return $this->longNamePt;
    }

    /**
    * Set nameMaleNameJa
    *
    * @param string $nameMaleNameJa
    * @return Emoticon
    */
    public function setNameMaleJa($nameMaleNameJa)
    {
        $this->nameMaleNameJa = $nameMaleNameJa;

        return $this;
    }

    /**
    * Get nameMaleNameJa
    *
    * @return string
    */
    public function getNameMaleJa()
    {
        return $this->nameMaleNameJa;
    }

    /**
    * Set nameMaleNameRu
    *
    * @param string $nameMaleNameRu
    * @return Emoticon
    */
    public function setNameMaleRu($nameMaleNameRu)
    {
        $this->nameMaleNameRu = $nameMaleNameRu;

        return $this;
    }

    /**
    * Get nameMaleNameRu
    *
    * @return string
    */
    public function getNameMaleRu()
    {
        return $this->nameMaleNameRu;
    }

    /**
    * Set nameMale
    *
    * @param string $nameMale
    * @param string $locale
    * @return Emoticon
    */
    public function setNameMale($nameMale, $locale = 'fr')
    {
        switch ($locale) {
            case 'fr': $this->nameMaleFr = $nameMale; break;
            case 'en': $this->nameMaleEn = $nameMale; break;
            case 'de': $this->nameMaleDe = $nameMale; break;
            case 'es': $this->nameMaleEs = $nameMale; break;
            case 'it': $this->nameMaleIt = $nameMale; break;
            case 'pt': $this->nameMalePt = $nameMale; break;
            case 'ja': $this->nameMaleJa = $nameMale; break;
            case 'ru': $this->nameMaleRu = $nameMale; break;
        }
        return $this;
    }

    /**
    * Get nameMale
    *
    * @param string|array $locale
    * @return string
    */
    public function getNameMale($locale = 'fr')
    {
        if (is_array($locale)) {
            foreach ($locale as $loc) {
                $nameMale = $this->getNameMale($loc);
                if ($nameMale !== null)
                return $nameMale;
            }
            return null;
        }
        switch ($locale) {
            case 'fr': return $this->nameMaleFr;
            case 'en': return $this->nameMaleEn;
            case 'de': return $this->nameMaleDe;
            case 'es': return $this->nameMaleEs;
            case 'it': return $this->nameMaleIt;
            case 'pt': return $this->nameMalePt;
            case 'ja': return $this->nameMaleJa;
            case 'ru': return $this->nameMaleRu;
            default: return null;
        }
    }
    /**
    * Set nameFemaleFr
    *
    * @param string $nameFemaleFr
    * @return Emoticon
    */
    public function setNameFemaleFr($nameFemaleFr)
    {
        $this->nameFemaleFr = $nameFemaleFr;

        return $this;
    }

    /**
    * Get nameFemaleNameFr
    *
    * @return string
    */
    public function getNameFemaleFr()
    {
        return $this->nameFemaleNameFr;
    }

    /**
    * Set nameFemaleNameEn
    *
    * @param string $nameFemaleNameEn
    * @return Emoticon
    */
    public function setNameFemaleEn($nameFemaleNameEn)
    {
        $this->nameFemaleNameEn = $nameFemaleNameEn;

        return $this;
    }

    /**
    * Get nameFemaleNameEn
    *
    * @return string
    */
    public function getNameFemaleEn()
    {
        return $this->nameFemaleNameEn;
    }

    /**
    * Set nameFemaleNameDe
    *
    * @param string $nameFemaleNameDe
    * @return Emoticon
    */
    public function setNameFemaleDe($nameFemaleNameDe)
    {
        $this->nameFemaleNameDe = $nameFemaleNameDe;

        return $this;
    }

    /**
    * Get nameFemaleNameDe
    *
    * @return string
    */
    public function getNameFemaleDe()
    {
        return $this->nameFemaleDe;
    }

    /**
    * Set nameFemaleEs
    *
    * @param string $nameFemaleEs
    * @return Emoticon
    */
    public function setNameFemaleEs($nameFemaleEs)
    {
        $this->nameFemaleEs = $nameFemaleEs;

        return $this;
    }

    /**
    * Get nameFemaleEs
    *
    * @return string
    */
    public function getNameFemaleEs()
    {
        return $this->nameFemaleEs;
    }

    /**
    * Set nameFemaleIt
    *
    * @param string $nameFemaleIt
    * @return Emoticon
    */
    public function setNameFemaleIt($nameFemaleIt)
    {
        $this->nameFemaleIt = $nameFemaleIt;

        return $this;
    }

    /**
    * Get nameFemaleIt
    *
    * @return string
    */
    public function getNameFemaleIt()
    {
        return $this->nameFemaleIt;
    }

    /**
    * Set nameFemalePt
    *
    * @param string $nameFemalePt
    * @return Emoticon
    */
    public function setNameFemalePt($nameFemalePt)
    {
        $this->nameFemalePt = $nameFemalePt;

        return $this;
    }

    /**
    * Get longNamePt
    *
    * @return string
    */
    public function getNameFemalePt()
    {
        return $this->longNamePt;
    }

    /**
    * Set nameFemaleNameJa
    *
    * @param string $nameFemaleNameJa
    * @return Emoticon
    */
    public function setNameFemaleJa($nameFemaleNameJa)
    {
        $this->nameFemaleNameJa = $nameFemaleNameJa;

        return $this;
    }

    /**
    * Get nameFemaleNameJa
    *
    * @return string
    */
    public function getNameFemaleJa()
    {
        return $this->nameFemaleNameJa;
    }

    /**
    * Set nameFemaleNameRu
    *
    * @param string $nameFemaleNameRu
    * @return Emoticon
    */
    public function setNameFemaleRu($nameFemaleNameRu)
    {
        $this->nameFemaleNameRu = $nameFemaleNameRu;

        return $this;
    }

    /**
    * Get nameFemaleNameRu
    *
    * @return string
    */
    public function getNameFemaleRu()
    {
        return $this->nameFemaleNameRu;
    }

    /**
    * Set nameFemale
    *
    * @param string $nameFemale
    * @param string $locale
    * @return Emoticon
    */
    public function setNameFemale($nameFemale, $locale = 'fr')
    {
        switch ($locale) {
            case 'fr': $this->nameFemaleFr = $nameFemale; break;
            case 'en': $this->nameFemaleEn = $nameFemale; break;
            case 'de': $this->nameFemaleDe = $nameFemale; break;
            case 'es': $this->nameFemaleEs = $nameFemale; break;
            case 'it': $this->nameFemaleIt = $nameFemale; break;
            case 'pt': $this->nameFemalePt = $nameFemale; break;
            case 'ja': $this->nameFemaleJa = $nameFemale; break;
            case 'ru': $this->nameFemaleRu = $nameFemale; break;
        }
        return $this;
    }

    /**
    * Get nameFemale
    *
    * @param string|array $locale
    * @return string
    */
    public function getNameFemale($locale = 'fr')
    {
        if (is_array($locale)) {
            foreach ($locale as $loc) {
                $nameFemale = $this->getNameFemale($loc);
                if ($nameFemale !== null)
                return $nameFemale;
            }
            return null;
        }
        switch ($locale) {
            case 'fr': return $this->nameFemaleFr;
            case 'en': return $this->nameFemaleEn;
            case 'de': return $this->nameFemaleDe;
            case 'es': return $this->nameFemaleEs;
            case 'it': return $this->nameFemaleIt;
            case 'pt': return $this->nameFemalePt;
            case 'ja': return $this->nameFemaleJa;
            case 'ru': return $this->nameFemaleRu;
            default: return null;
        }
    }

    public function __toString(){
        return $this->nameMaleFr;
    }
}
