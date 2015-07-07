<?php

namespace Dof\Bundle\CharacterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;
use Dof\Bundle\CharacterBundle\Gender;

/**
 * Breed.
 *
 * @ORM\Table(name="dof_breeds")
 * @ORM\Entity(repositoryClass="BreedRepository")
 */
class Breed implements IdentifiableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, SluggableTrait, ReleaseBoundTrait, LocalizedNameTrait, LocalizedDescriptionTrait;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="long_name", type="string", length=255, nullable=true)
     */
    private $longName;

    /**
     * @var text
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="gameplay_description", type="text", nullable=true)
     */
    private $gameplayDescription;

    /**
     * @var int
     *
     * @ORM\Column(name="male_skin", type="integer", unique=true)
     */
    private $maleSkin;

    /**
     * @var int
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
     * @var int
     *
     * @ORM\Column(name="male_size", type="integer")
     */
    private $maleSize;

    /**
     * @var int
     *
     * @ORM\Column(name="male_artwork_bone", type="integer", unique=true)
     */
    private $maleArtworkBone;

    /**
     * @var int
     *
     * @ORM\Column(name="female_skin", type="integer", unique=true)
     */
    private $femaleSkin;

    /**
     * @var int
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
     * @var int
     *
     * @ORM\Column(name="female_size", type="integer")
     */
    private $femaleSize;

    /**
     * @var int
     *
     * @ORM\Column(name="female_artwork_bone", type="integer", unique=true)
     */
    private $femaleArtworkBone;

    /**
     * @var int
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
     * @ORM\ManyToMany(targetEntity="Dof\Bundle\CharacterBundle\Entity\Spell", mappedBy="breeds")
     */
    private $spells;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    public function __construct()
    {
        $this->faces = new ArrayCollection();
        $this->softCaps = new ArrayCollection();
        $this->spells = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Face
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set longName.
     *
     * @param string $longName
     *
     * @return Breed
     */
    public function setLongName($longName)
    {
        $this->longName = $longName;

        return $this;
    }

    /**
     * Get longName.
     *
     * @return string
     */
    public function getLongName()
    {
        return $this->longName;
    }

    /**
     * Set gameplayDescription.
     *
     * @param string $gameplayDescription
     *
     * @return Breed
     */
    public function setGameplayDescription($gameplayDescription)
    {
        $this->gameplayDescription = $gameplayDescription;

        return $this;
    }

    /**
     * Get gameplayDescription.
     *
     * @return string
     */
    public function getGameplayDescription()
    {
        return $this->gameplayDescription;
    }

    /**
     * Set maleSkin.
     *
     * @param int $maleSkin
     *
     * @return Breed
     */
    public function setMaleSkin($maleSkin)
    {
        $this->maleSkin = $maleSkin;

        return $this;
    }

    /**
     * Get maleSkin.
     *
     * @return int
     */
    public function getMaleSkin()
    {
        return $this->maleSkin;
    }

    /**
     * Set maleLodefSkin.
     *
     * @param int $maleLodefSkin
     *
     * @return Breed
     */
    public function setMaleLodefSkin($maleLodefSkin)
    {
        $this->maleLodefSkin = $maleLodefSkin;

        return $this;
    }

    /**
     * Get maleLodefSkin.
     *
     * @return int
     */
    public function getMaleLodefSkin()
    {
        return $this->maleLodefSkin;
    }

    /**
     * Set maleDefaultColors.
     *
     * @param array $maleDefaultColors
     *
     * @return Breed
     */
    public function setMaleDefaultColors(array $maleDefaultColors)
    {
        $this->maleDefaultColors = $maleDefaultColors;

        return $this;
    }

    /**
     * Get maleDefaultColors.
     *
     * @return array
     */
    public function getMaleDefaultColors()
    {
        return $this->maleDefaultColors;
    }

    /**
     * Set maleSize.
     *
     * @param int $maleSize
     *
     * @return Breed
     */
    public function setMaleSize($maleSize)
    {
        $this->maleSize = $maleSize;

        return $this;
    }

    /**
     * Get maleSize.
     *
     * @return int
     */
    public function getMaleSize()
    {
        return $this->maleSize;
    }

    /**
     * Set maleArtworkBone.
     *
     * @param int $maleArtworkBone
     *
     * @return Breed
     */
    public function setMaleArtworkBone($maleArtworkBone)
    {
        $this->maleArtworkBone = $maleArtworkBone;

        return $this;
    }

    /**
     * Get maleArtworkBone.
     *
     * @return int
     */
    public function getMaleArtworkBone()
    {
        return $this->maleArtworkBone;
    }

    /**
     * Set femaleSkin.
     *
     * @param int $femaleSkin
     *
     * @return Breed
     */
    public function setFemaleSkin($femaleSkin)
    {
        $this->femaleSkin = $femaleSkin;

        return $this;
    }

    /**
     * Get femaleSkin.
     *
     * @return int
     */
    public function getFemaleSkin()
    {
        return $this->femaleSkin;
    }

    /**
     * Set femaleLodefSkin.
     *
     * @param int $femaleLodefSkin
     *
     * @return Breed
     */
    public function setFemaleLodefSkin($femaleLodefSkin)
    {
        $this->femaleLodefSkin = $femaleLodefSkin;

        return $this;
    }

    /**
     * Get femaleLodefSkin.
     *
     * @return int
     */
    public function getFemaleLodefSkin()
    {
        return $this->femaleLodefSkin;
    }

    /**
     * Set femaleDefaultColors.
     *
     * @param array $femaleDefaultColors
     *
     * @return Breed
     */
    public function setFemaleDefaultColors(array $femaleDefaultColors)
    {
        $this->femaleDefaultColors = $femaleDefaultColors;

        return $this;
    }

    /**
     * Get femaleDefaultColors.
     *
     * @return array
     */
    public function getFemaleDefaultColors()
    {
        return $this->femaleDefaultColors;
    }

    /**
     * Set femaleSize.
     *
     * @param int $femaleSize
     *
     * @return Breed
     */
    public function setFemaleSize($femaleSize)
    {
        $this->femaleSize = $femaleSize;

        return $this;
    }

    /**
     * Get femaleSize.
     *
     * @return int
     */
    public function getFemaleSize()
    {
        return $this->femaleSize;
    }

    /**
     * Set femaleArtworkBone.
     *
     * @param int $femaleArtworkBone
     *
     * @return Breed
     */
    public function setFemaleArtworkBone($femaleArtworkBone)
    {
        $this->femaleArtworkBone = $femaleArtworkBone;

        return $this;
    }

    /**
     * Get femaleArtworkBone.
     *
     * @return int
     */
    public function getFemaleArtworkBone()
    {
        return $this->femaleArtworkBone;
    }

    /**
     * Set creatureBone.
     *
     * @param int $creatureBone
     *
     * @return Breed
     */
    public function setCreatureBone($creatureBone)
    {
        $this->creatureBone = $creatureBone;

        return $this;
    }

    /**
     * Get creatureBone.
     *
     * @return int
     */
    public function getCreatureBone()
    {
        return $this->creatureBone;
    }

    /**
     * Add faces.
     *
     * @param Face $faces
     *
     * @return Breed
     */
    public function addFace(Face $faces)
    {
        $this->faces[] = $faces;

        return $this;
    }

    /**
     * Remove faces.
     *
     * @param Face $faces
     *
     * @return Breed
     */
    public function removeFace(Face $faces)
    {
        $this->faces->removeElement($faces);

        return $this;
    }

    /**
     * Get faces.
     *
     * @return Collection
     */
    public function getFaces()
    {
        return $this->faces;
    }

    public function getFace($gender = Gender::MALE, $label = 'I')
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where($expr->eq('gender', $gender));
        $criteria->andWhere($expr->eq('label', $label));

        return $this->faces->matching($criteria)->first();
    }

    /**
     * Add softCaps.
     *
     * @param SoftCap $softCaps
     *
     * @return Breed
     */
    public function addSoftCap(SoftCap $softCaps)
    {
        $this->softCaps[] = $softCaps;

        return $this;
    }

    /**
     * Remove softCaps.
     *
     * @param SoftCap $softCaps
     *
     * @return Breed
     */
    public function removeSoftCap(SoftCap $softCaps)
    {
        $this->softCaps->removeElement($softCaps);

        return $this;
    }

    /**
     * Get softCaps.
     *
     * @return Collection
     */
    public function getSoftCaps()
    {
        return $this->softCaps;
    }

    /**
     * Add spells.
     *
     * @param Spell $spells
     *
     * @return Breed
     */
    public function addSpell(Spell $spells)
    {
        $this->spells[] = $spells;

        return $this;
    }

    /**
     * Remove spells.
     *
     * @param Spell $spells
     *
     * @return Breed
     */
    public function removeSpell(Spell $spells)
    {
        $this->spells->removeElement($spells);

        return $this;
    }

    /**
     * Get spells.
     *
     * @return Collection
     */
    public function getSpells()
    {
        return $this->spells;
    }

    public function getSortedSpells()
    {
        $spells = $this->spells->toArray();
        usort($spells, function ($a, $b) {
            $aLevel = $a->getRanks()[0]->getObtainmentLevel();
            $bLevel = $b->getRanks()[0]->getObtainmentLevel();

            return $aLevel - $bLevel;
        });

        return $spells;
    }

    public function __toString()
    {
        return $this->name;
    }
}
