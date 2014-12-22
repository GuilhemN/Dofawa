<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * Breed
 *
 * @ORM\Table(name="dof_breeds")
 * @ORM\Entity(repositoryClass="BreedRepository")
 */
class Breed implements IdentifiableInterface, TimestampableInterface, SluggableInterface, LocalizedNameInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, SluggableTrait, ReleaseBoundTrait, LocalizedNameTrait, LocalizedDescriptionTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="long_name_fr", type="string", length=255)
     */
    private $longNameFr;

    /**
     * @var string
     *
     * @ORM\Column(name="long_name_en", type="string", length=255, nullable=true)
     */
    private $longNameEn;

    /**
     * @var string
     *
     * @ORM\Column(name="long_name_de", type="string", length=255, nullable=true)
     */
    private $longNameDe;

    /**
     * @var string
     *
     * @ORM\Column(name="long_name_es", type="string", length=255, nullable=true)
     */
    private $longNameEs;

    /**
     * @var string
     *
     * @ORM\Column(name="long_name_it", type="string", length=255, nullable=true)
     */
    private $longNameIt;

    /**
     * @var string
     *
     * @ORM\Column(name="long_name_pt", type="string", length=255, nullable=true)
     */
    private $longNamePt;

    /**
     * @var string
     *
     * @ORM\Column(name="long_name_ja", type="string", length=255, nullable=true)
     */
    private $longNameJa;

    /**
     * @var string
     *
     * @ORM\Column(name="long_name_ru", type="string", length=255, nullable=true)
     */
    private $longNameRu;

    /**
     * @var string
     *
     * @ORM\Column(name="gameplay_description_fr", type="string", length=255)
     */
    private $gameplayDescriptionFr;

    /**
     * @var string
     *
     * @ORM\Column(name="gameplay_description_en", type="string", length=255, nullable=true)
     */
    private $gameplayDescriptionEn;

    /**
     * @var string
     *
     * @ORM\Column(name="gameplay_description_de", type="string", length=255, nullable=true)
     */
    private $gameplayDescriptionDe;

    /**
     * @var string
     *
     * @ORM\Column(name="gameplay_description_es", type="string", length=255, nullable=true)
     */
    private $gameplayDescriptionEs;

    /**
     * @var string
     *
     * @ORM\Column(name="gameplay_description_it", type="string", length=255, nullable=true)
     */
    private $gameplayDescriptionIt;

    /**
     * @var string
     *
     * @ORM\Column(name="gameplay_description_pt", type="string", length=255, nullable=true)
     */
    private $gameplayDescriptionPt;

    /**
     * @var string
     *
     * @ORM\Column(name="gameplay_description_ja", type="string", length=255, nullable=true)
     */
    private $gameplayDescriptionJa;

    /**
     * @var string
     *
     * @ORM\Column(name="gameplay_description_ru", type="string", length=255, nullable=true)
     */
    private $gameplayDescriptionRu;

    /**
     * @var integer
     *
     * @ORM\Column(name="male_skin", type="integer", unique=true)
     */
    private $maleSkin;

    /**
     * @var integer
     *
     * @ORM\Column(name="male_lodef_skin", type="integer", unique=true)
     */
    private $maleLodefSkin;

    /**
     * @var array
     *
     * @ORM\Column(name="male_default_colors", type="json_array")
     */
    private $maleDefaultColors;

    /**
     * @var integer
     *
     * @ORM\Column(name="male_size", type="integer")
     */
    private $maleSize;

    /**
     * @var integer
     *
     * @ORM\Column(name="male_artwork_bone", type="integer", unique=true)
     */
    private $maleArtworkBone;

    /**
     * @var integer
     *
     * @ORM\Column(name="female_skin", type="integer", unique=true)
     */
    private $femaleSkin;

    /**
     * @var integer
     *
     * @ORM\Column(name="female_lodef_skin", type="integer", unique=true)
     */
    private $femaleLodefSkin;

    /**
     * @var array
     *
     * @ORM\Column(name="female_default_colors", type="json_array")
     */
    private $femaleDefaultColors;

    /**
     * @var integer
     *
     * @ORM\Column(name="female_size", type="integer")
     */
    private $femaleSize;

    /**
     * @var integer
     *
     * @ORM\Column(name="female_artwork_bone", type="integer", unique=true)
     */
    private $femaleArtworkBone;

    /**
     * @var integer
     *
     * @ORM\Column(name="creature_bone", type="integer", unique=true)
     */
    private $creatureBone;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Face", mappedBy="breed")
     * @ORM\OrderBy({ "gender" = "ASC", "order" = "ASC", "id" = "ASC" })
     */
    private $faces;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="SoftCap", mappedBy="breed")
     * @ORM\OrderBy({ "characteristic" = "ASC", "min" = "ASC", "id" = "ASC" })
     */
    private $softCaps;

    /**
    * @ORM\ManyToMany(targetEntity="Dof\CharactersBundle\Entity\Spell", mappedBy="breeds")
    */
    private $spells;

    public function __construct()
    {
        $this->faces = new ArrayCollection();
        $this->softCaps = new ArrayCollection();
        $this->spells = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Face
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
     * Set longNameFr
     *
     * @param string $longNameFr
     * @return Breed
     */
    public function setLongNameFr($longNameFr)
    {
        $this->longNameFr = $longNameFr;

        return $this;
    }

    /**
     * Get longNameFr
     *
     * @return string
     */
    public function getLongNameFr()
    {
        return $this->longNameFr;
    }

    /**
     * Set longNameEn
     *
     * @param string $longNameEn
     * @return Breed
     */
    public function setLongNameEn($longNameEn)
    {
        $this->longNameEn = $longNameEn;

        return $this;
    }

    /**
     * Get longNameEn
     *
     * @return string
     */
    public function getLongNameEn()
    {
        return $this->longNameEn;
    }

    /**
     * Set longNameDe
     *
     * @param string $longNameDe
     * @return Breed
     */
    public function setLongNameDe($longNameDe)
    {
        $this->longNameDe = $longNameDe;

        return $this;
    }

    /**
     * Get longNameDe
     *
     * @return string
     */
    public function getLongNameDe()
    {
        return $this->longNameDe;
    }

    /**
     * Set longNameEs
     *
     * @param string $longNameEs
     * @return Breed
     */
    public function setLongNameEs($longNameEs)
    {
        $this->longNameEs = $longNameEs;

        return $this;
    }

    /**
     * Get longNameEs
     *
     * @return string
     */
    public function getLongNameEs()
    {
        return $this->longNameEs;
    }

    /**
     * Set longNameIt
     *
     * @param string $longNameIt
     * @return Breed
     */
    public function setLongNameIt($longNameIt)
    {
        $this->longNameIt = $longNameIt;

        return $this;
    }

    /**
     * Get longNameIt
     *
     * @return string
     */
    public function getLongNameIt()
    {
        return $this->longNameIt;
    }

    /**
     * Set longNamePt
     *
     * @param string $longNamePt
     * @return Breed
     */
    public function setLongNamePt($longNamePt)
    {
        $this->longNamePt = $longNamePt;

        return $this;
    }

    /**
     * Get longNamePt
     *
     * @return string
     */
    public function getLongNamePt()
    {
        return $this->longNamePt;
    }

    /**
     * Set longNameJa
     *
     * @param string $longNameJa
     * @return Breed
     */
    public function setLongNameJa($longNameJa)
    {
        $this->longNameJa = $longNameJa;

        return $this;
    }

    /**
     * Get longNameJa
     *
     * @return string
     */
    public function getLongNameJa()
    {
        return $this->longNameJa;
    }

    /**
     * Set longNameRu
     *
     * @param string $longNameRu
     * @return Breed
     */
    public function setLongNameRu($longNameRu)
    {
        $this->longNameRu = $longNameRu;

        return $this;
    }

    /**
     * Get longNameRu
     *
     * @return string
     */
    public function getLongNameRu()
    {
        return $this->longNameRu;
    }

    /**
     * Set longName
     *
     * @param string $longName
     * @param string $locale
     * @return Breed
     */
    public function setLongName($longName, $locale = 'fr')
    {
        switch ($locale) {
            case 'fr': $this->longNameFr = $longName; break;
            case 'en': $this->longNameEn = $longName; break;
            case 'de': $this->longNameDe = $longName; break;
            case 'es': $this->longNameEs = $longName; break;
            case 'it': $this->longNameIt = $longName; break;
            case 'pt': $this->longNamePt = $longName; break;
            case 'ja': $this->longNameJa = $longName; break;
            case 'ru': $this->longNameRu = $longName; break;
        }
        return $this;
    }

    /**
     * Get longName
     *
     * @param string|array $locale
     * @return string
     */
    public function getLongName($locale = 'fr')
    {
        if (is_array($locale)) {
            foreach ($locale as $loc) {
                $longName = $this->getLongName($loc);
                if ($longName !== null)
                    return $longName;
            }
            return null;
        }
        switch ($locale) {
            case 'fr': return $this->longNameFr;
            case 'en': return $this->longNameEn;
            case 'de': return $this->longNameDe;
            case 'es': return $this->longNameEs;
            case 'it': return $this->longNameIt;
            case 'pt': return $this->longNamePt;
            case 'ja': return $this->longNameJa;
            case 'ru': return $this->longNameRu;
            default: return null;
        }
    }

    /**
     * Set gameplayDescriptionFr
     *
     * @param string $gameplayDescriptionFr
     * @return Breed
     */
    public function setGameplayDescriptionFr($gameplayDescriptionFr)
    {
        $this->gameplayDescriptionFr = $gameplayDescriptionFr;

        return $this;
    }

    /**
     * Get gameplayDescriptionFr
     *
     * @return string
     */
    public function getGameplayDescriptionFr()
    {
        return $this->gameplayDescriptionFr;
    }

    /**
     * Set gameplayDescriptionEn
     *
     * @param string $gameplayDescriptionEn
     * @return Breed
     */
    public function setGameplayDescriptionEn($gameplayDescriptionEn)
    {
        $this->gameplayDescriptionEn = $gameplayDescriptionEn;

        return $this;
    }

    /**
     * Get gameplayDescriptionEn
     *
     * @return string
     */
    public function getGameplayDescriptionEn()
    {
        return $this->gameplayDescriptionEn;
    }

    /**
     * Set gameplayDescriptionDe
     *
     * @param string $gameplayDescriptionDe
     * @return Breed
     */
    public function setGameplayDescriptionDe($gameplayDescriptionDe)
    {
        $this->gameplayDescriptionDe = $gameplayDescriptionDe;

        return $this;
    }

    /**
     * Get gameplayDescriptionDe
     *
     * @return string
     */
    public function getGameplayDescriptionDe()
    {
        return $this->gameplayDescriptionDe;
    }

    /**
     * Set gameplayDescriptionEs
     *
     * @param string $gameplayDescriptionEs
     * @return Breed
     */
    public function setGameplayDescriptionEs($gameplayDescriptionEs)
    {
        $this->gameplayDescriptionEs = $gameplayDescriptionEs;

        return $this;
    }

    /**
     * Get gameplayDescriptionEs
     *
     * @return string
     */
    public function getGameplayDescriptionEs()
    {
        return $this->gameplayDescriptionEs;
    }

    /**
     * Set gameplayDescriptionIt
     *
     * @param string $gameplayDescriptionIt
     * @return Breed
     */
    public function setGameplayDescriptionIt($gameplayDescriptionIt)
    {
        $this->gameplayDescriptionIt = $gameplayDescriptionIt;

        return $this;
    }

    /**
     * Get gameplayDescriptionIt
     *
     * @return string
     */
    public function getGameplayDescriptionIt()
    {
        return $this->gameplayDescriptionIt;
    }

    /**
     * Set gameplayDescriptionPt
     *
     * @param string $gameplayDescriptionPt
     * @return Breed
     */
    public function setGameplayDescriptionPt($gameplayDescriptionPt)
    {
        $this->gameplayDescriptionPt = $gameplayDescriptionPt;

        return $this;
    }

    /**
     * Get gameplayDescriptionPt
     *
     * @return string
     */
    public function getGameplayDescriptionPt()
    {
        return $this->gameplayDescriptionPt;
    }

    /**
     * Set gameplayDescriptionJa
     *
     * @param string $gameplayDescriptionJa
     * @return Breed
     */
    public function setGameplayDescriptionJa($gameplayDescriptionJa)
    {
        $this->gameplayDescriptionJa = $gameplayDescriptionJa;

        return $this;
    }

    /**
     * Get gameplayDescriptionJa
     *
     * @return string
     */
    public function getGameplayDescriptionJa()
    {
        return $this->gameplayDescriptionJa;
    }

    /**
     * Set gameplayDescriptionRu
     *
     * @param string $gameplayDescriptionRu
     * @return Breed
     */
    public function setGameplayDescriptionRu($gameplayDescriptionRu)
    {
        $this->gameplayDescriptionRu = $gameplayDescriptionRu;

        return $this;
    }

    /**
     * Get gameplayDescriptionRu
     *
     * @return string
     */
    public function getGameplayDescriptionRu()
    {
        return $this->gameplayDescriptionRu;
    }

    /**
     * Set gameplayDescription
     *
     * @param string $gameplayDescription
     * @param string $locale
     * @return Breed
     */
    public function setGameplayDescription($gameplayDescription, $locale = 'fr')
    {
        switch ($locale) {
            case 'fr': $this->gameplayDescriptionFr = $gameplayDescription; break;
            case 'en': $this->gameplayDescriptionEn = $gameplayDescription; break;
            case 'de': $this->gameplayDescriptionDe = $gameplayDescription; break;
            case 'es': $this->gameplayDescriptionEs = $gameplayDescription; break;
            case 'it': $this->gameplayDescriptionIt = $gameplayDescription; break;
            case 'pt': $this->gameplayDescriptionPt = $gameplayDescription; break;
            case 'ja': $this->gameplayDescriptionJa = $gameplayDescription; break;
            case 'ru': $this->gameplayDescriptionRu = $gameplayDescription; break;
        }
        return $this;
    }

    /**
     * Get gameplayDescription
     *
     * @param string|array $locale
     * @return string
     */
    public function getGameplayDescription($locale = 'fr')
    {
        if (is_array($locale)) {
            foreach ($locale as $loc) {
                $gameplayDescription = $this->getGameplayDescription($loc);
                if ($gameplayDescription !== null)
                    return $gameplayDescription;
            }
            return null;
        }
        switch ($locale) {
            case 'fr': return $this->gameplayDescriptionFr;
            case 'en': return $this->gameplayDescriptionEn;
            case 'de': return $this->gameplayDescriptionDe;
            case 'es': return $this->gameplayDescriptionEs;
            case 'it': return $this->gameplayDescriptionIt;
            case 'pt': return $this->gameplayDescriptionPt;
            case 'ja': return $this->gameplayDescriptionJa;
            case 'ru': return $this->gameplayDescriptionRu;
            default: return null;
        }
    }

    /**
     * Set maleSkin
     *
     * @param integer $maleSkin
     * @return Breed
     */
    public function setMaleSkin($maleSkin)
    {
        $this->maleSkin = $maleSkin;

        return $this;
    }

    /**
     * Get maleSkin
     *
     * @return integer
     */
    public function getMaleSkin()
    {
        return $this->maleSkin;
    }

    /**
     * Set maleLodefSkin
     *
     * @param integer $maleLodefSkin
     * @return Breed
     */
    public function setMaleLodefSkin($maleLodefSkin)
    {
        $this->maleLodefSkin = $maleLodefSkin;

        return $this;
    }

    /**
     * Get maleLodefSkin
     *
     * @return integer
     */
    public function getMaleLodefSkin()
    {
        return $this->maleLodefSkin;
    }

    /**
     * Set maleDefaultColors
     *
     * @param array $maleDefaultColors
     * @return Breed
     */
    public function setMaleDefaultColors(array $maleDefaultColors)
    {
        $this->maleDefaultColors = $maleDefaultColors;

        return $this;
    }

    /**
     * Get maleDefaultColors
     *
     * @return array
     */
    public function getMaleDefaultColors()
    {
        return $this->maleDefaultColors;
    }

    /**
     * Set maleSize
     *
     * @param integer $maleSize
     * @return Breed
     */
    public function setMaleSize($maleSize)
    {
        $this->maleSize = $maleSize;

        return $this;
    }

    /**
     * Get maleSize
     *
     * @return integer
     */
    public function getMaleSize()
    {
        return $this->maleSize;
    }

    /**
     * Set maleArtworkBone
     *
     * @param integer $maleArtworkBone
     * @return Breed
     */
    public function setMaleArtworkBone($maleArtworkBone)
    {
        $this->maleArtworkBone = $maleArtworkBone;

        return $this;
    }

    /**
     * Get maleArtworkBone
     *
     * @return integer
     */
    public function getMaleArtworkBone()
    {
        return $this->maleArtworkBone;
    }

    /**
     * Set femaleSkin
     *
     * @param integer $femaleSkin
     * @return Breed
     */
    public function setFemaleSkin($femaleSkin)
    {
        $this->femaleSkin = $femaleSkin;

        return $this;
    }

    /**
     * Get femaleSkin
     *
     * @return integer
     */
    public function getFemaleSkin()
    {
        return $this->femaleSkin;
    }

    /**
     * Set femaleLodefSkin
     *
     * @param integer $femaleLodefSkin
     * @return Breed
     */
    public function setFemaleLodefSkin($femaleLodefSkin)
    {
        $this->femaleLodefSkin = $femaleLodefSkin;

        return $this;
    }

    /**
     * Get femaleLodefSkin
     *
     * @return integer
     */
    public function getFemaleLodefSkin()
    {
        return $this->femaleLodefSkin;
    }

    /**
     * Set femaleDefaultColors
     *
     * @param array $femaleDefaultColors
     * @return Breed
     */
    public function setFemaleDefaultColors(array $femaleDefaultColors)
    {
        $this->femaleDefaultColors = $femaleDefaultColors;

        return $this;
    }

    /**
     * Get femaleDefaultColors
     *
     * @return array
     */
    public function getFemaleDefaultColors()
    {
        return $this->femaleDefaultColors;
    }

    /**
     * Set femaleSize
     *
     * @param integer $femaleSize
     * @return Breed
     */
    public function setFemaleSize($femaleSize)
    {
        $this->femaleSize = $femaleSize;

        return $this;
    }

    /**
     * Get femaleSize
     *
     * @return integer
     */
    public function getFemaleSize()
    {
        return $this->femaleSize;
    }

    /**
     * Set femaleArtworkBone
     *
     * @param integer $femaleArtworkBone
     * @return Breed
     */
    public function setFemaleArtworkBone($femaleArtworkBone)
    {
        $this->femaleArtworkBone = $femaleArtworkBone;

        return $this;
    }

    /**
     * Get femaleArtworkBone
     *
     * @return integer
     */
    public function getFemaleArtworkBone()
    {
        return $this->femaleArtworkBone;
    }

    /**
     * Set creatureBone
     *
     * @param integer $creatureBone
     * @return Breed
     */
    public function setCreatureBone($creatureBone)
    {
        $this->creatureBone = $creatureBone;

        return $this;
    }

    /**
     * Get creatureBone
     *
     * @return integer
     */
    public function getCreatureBone()
    {
        return $this->creatureBone;
    }

    /**
     * Add faces
     *
     * @param Face $faces
     * @return Breed
     */
    public function addFace(Face $faces)
    {
        $this->faces[] = $faces;

        return $this;
    }

    /**
     * Remove faces
     *
     * @param Face $faces
     * @return Breed
     */
    public function removeFace(Face $faces)
    {
        $this->faces->removeElement($faces);

        return $this;
    }

    /**
     * Get faces
     *
     * @return Collection
     */
    public function getFaces()
    {
        return $this->faces;
    }

    /**
     * Add softCaps
     *
     * @param SoftCap $softCaps
     * @return Breed
     */
    public function addSoftCap(SoftCap $softCaps)
    {
        $this->softCaps[] = $softCaps;

        return $this;
    }

    /**
     * Remove softCaps
     *
     * @param SoftCap $softCaps
     * @return Breed
     */
    public function removeSoftCap(SoftCap $softCaps)
    {
        $this->softCaps->removeElement($softCaps);

        return $this;
    }

    /**
     * Get softCaps
     *
     * @return Collection
     */
    public function getSoftCaps()
    {
        return $this->softCaps;
    }

    /**
    * Add spells
    *
    * @param Spell $spells
    * @return Breed
    */
    public function addSpell(Spell $spells)
    {
        $this->spells[] = $spells;

        return $this;
    }

    /**
    * Remove spells
    *
    * @param Spell $spells
    * @return Breed
    */
    public function removeSpell(Spell $spells)
    {
        $this->spells->removeElement($spells);

        return $this;
    }

    /**
    * Get spells
    *
    * @return Collection
    */
    public function getSpells()
    {
        return $this->spells;
    }

    public function getSortedSpells(){
        return $this->spells->toArray();
    }

    public function __toString()
    {
        return $this->nameFr;
    }
}
