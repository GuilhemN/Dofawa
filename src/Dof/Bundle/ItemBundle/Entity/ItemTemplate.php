<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use XN\Common\UrlSafeBase64;
use XN\Rest\ExportableInterface;
use XN\Rest\ImportableTrait;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;
use XN\Metadata\FileInterface;
use XN\Metadata\FileLightTrait;
use Dof\Bundle\ItemBundle\Criteria\ParsedCriteriaTrait;
use Dof\Bundle\ItemBundle\Criteria\ParsedCriteriaInterface;
use Dof\Bundle\MonsterBundle\Entity\MonsterDrop;

/**
 * ItemTemplate.
 *
 * @ORM\Table(name="dof_item_templates", indexes={ @ORM\Index(name="IX_item_skin", columns={ "skin" }), @ORM\Index(name="IX_item_bone", columns={ "bone" }) })
 * @ORM\Entity(repositoryClass="ItemTemplateRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({"item" = "ItemTemplate", "equip" = "EquipmentTemplate", "skequip" = "SkinnedEquipmentTemplate", "weapon" = "WeaponTemplate", "animal" = "AnimalTemplate", "pet" = "PetTemplate", "mount" = "MountTemplate", "useable" = "UseableItemTemplate"})
 */
class ItemTemplate implements IdentifiableInterface, ExportableInterface, LocalizedNameInterface, FileInterface, ParsedCriteriaInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, SluggableTrait, ImportableTrait, ReleaseBoundTrait, LocalizedNameTrait, LocalizedDescriptionTrait, FileLightTrait, ParsedCriteriaTrait;

    /**
     * @var ItemType
     *
     * @ORM\ManyToOne(targetEntity="ItemType", inversedBy="items")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="obtainment_fr", type="text", nullable=true)
     */
    private $obtainmentFr;

    /**
     * @var string
     *
     * @ORM\Column(name="obtainment_en", type="text", nullable=true)
     */
    private $obtainmentEn;

    /**
     * @var string
     *
     * @ORM\Column(name="obtainment_de", type="text", nullable=true)
     */
    private $obtainmentDe;

    /**
     * @var string
     *
     * @ORM\Column(name="obtainment_es", type="text", nullable=true)
     */
    private $obtainmentEs;

    /**
     * @var string
     *
     * @ORM\Column(name="obtainment_it", type="text", nullable=true)
     */
    private $obtainmentIt;

    /**
     * @var string
     *
     * @ORM\Column(name="obtainment_pt", type="text", nullable=true)
     */
    private $obtainmentPt;

    /**
     * @var string
     *
     * @ORM\Column(name="obtainment_ja", type="text", nullable=true)
     */
    private $obtainmentJa;

    /**
     * @var string
     *
     * @ORM\Column(name="obtainment_ru", type="text", nullable=true)
     */
    private $obtainmentRu;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_relative_path", type="string", length=31, nullable=true)
     */
    private $iconRelativePath;

    /**
     * @var int
     *
     * @ORM\Column(name="icon_id", type="integer", nullable=true)
     */
    private $iconId;

    /**
     * @var int
     *
     * @ORM\Column(name="dominant_color", type="integer", nullable=true)
     */
    private $dominantColor;

    /**
     * @var string
     *
     * @ORM\Column(name="criteria", type="string", length=127, nullable=true)
     */
    private $criteria;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;

    /**
     * @var bool
     *
     * @ORM\Column(name="tradeable", type="boolean")
     */
    private $tradeable;

    /**
     * @var int
     *
     * @ORM\Column(name="npc_price", type="integer")
     */
    private $npcPrice;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemTemplateEffect", mappedBy="item")
     * @ORM\OrderBy({ "order" = "ASC", "id" = "ASC" })
     */
    private $effects;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemComponent", mappedBy="compound")
     */
    private $components;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemComponent", mappedBy="component")
     */
    private $compounds;

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * @var bool
     *
     * @ORM\Column(name="sticky", type="boolean")
     */
    private $sticky;

    /**
     * @var Job
     *
     * @ORM\ManyToOne(targetEntity="Job", inversedBy="gatherableItems")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $gatheringJob;

    /**
     * @var int
     *
     * @ORM\Column(name="gathering_job_min_level", type="integer", nullable=true)
     */
    private $gatheringJobMinLevel;

    /**
     * @var Job
     *
     * @ORM\ManyToOne(targetEntity="Job", inversedBy="craftableItems")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $craftingJob;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Dof\Bundle\MonsterBundle\Entity\MonsterDrop", mappedBy="object")
     */
    private $drops;

    /**
     * @Assert\Image(
     *     maxSize = "1024k",
     *     minWidth = 131,
     *     maxWidth = 200,
     *     minHeight = 131,
     *     maxHeight = 200,
     *     mimeTypesMessage = "Choisissez un fichier image valide.")
     */
    private $file;

    public function __construct()
    {
        $this->effects = new ArrayCollection();
        $this->components = new ArrayCollection();
        $this->compounds = new ArrayCollection();
        $this->drops = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return ItemTemplate
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
     * Set type.
     *
     * @param ItemType $type
     *
     * @return ItemTemplate
     */
    public function setType(ItemType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return ItemType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set obtainmentFr.
     *
     * @param string $obtainmentFr
     *
     * @return ItemTemplate
     */
    public function setObtainmentFr($obtainmentFr)
    {
        $this->obtainmentFr = $obtainmentFr;

        return $this;
    }

    /**
     * Get obtainmentFr.
     *
     * @return string
     */
    public function getObtainmentFr()
    {
        return $this->obtainmentFr;
    }

    /**
     * Set obtainmentEn.
     *
     * @param string $obtainmentEn
     *
     * @return ItemTemplate
     */
    public function setObtainmentEn($obtainmentEn)
    {
        $this->obtainmentEn = $obtainmentEn;

        return $this;
    }

    /**
     * Get obtainmentEn.
     *
     * @return string
     */
    public function getObtainmentEn()
    {
        return $this->obtainmentEn;
    }

    /**
     * Set obtainmentDe.
     *
     * @param string $obtainmentDe
     *
     * @return ItemTemplate
     */
    public function setObtainmentDe($obtainmentDe)
    {
        $this->obtainmentDe = $obtainmentDe;

        return $this;
    }

    /**
     * Get obtainmentDe.
     *
     * @return string
     */
    public function getObtainmentDe()
    {
        return $this->obtainmentDe;
    }

    /**
     * Set obtainmentEs.
     *
     * @param string $obtainmentEs
     *
     * @return ItemTemplate
     */
    public function setObtainmentEs($obtainmentEs)
    {
        $this->obtainmentEs = $obtainmentEs;

        return $this;
    }

    /**
     * Get obtainmentEs.
     *
     * @return string
     */
    public function getObtainmentEs()
    {
        return $this->obtainmentEs;
    }

    /**
     * Set obtainmentIt.
     *
     * @param string $obtainmentIt
     *
     * @return ItemTemplate
     */
    public function setObtainmentIt($obtainmentIt)
    {
        $this->obtainmentIt = $obtainmentIt;

        return $this;
    }

    /**
     * Get obtainmentIt.
     *
     * @return string
     */
    public function getObtainmentIt()
    {
        return $this->obtainmentIt;
    }

    /**
     * Set obtainmentPt.
     *
     * @param string $obtainmentPt
     *
     * @return ItemTemplate
     */
    public function setObtainmentPt($obtainmentPt)
    {
        $this->obtainmentPt = $obtainmentPt;

        return $this;
    }

    /**
     * Get obtainmentPt.
     *
     * @return string
     */
    public function getObtainmentPt()
    {
        return $this->obtainmentPt;
    }

    /**
     * Set obtainmentJa.
     *
     * @param string $obtainmentJa
     *
     * @return ItemTemplate
     */
    public function setObtainmentJa($obtainmentJa)
    {
        $this->obtainmentJa = $obtainmentJa;

        return $this;
    }

    /**
     * Get obtainmentJa.
     *
     * @return string
     */
    public function getObtainmentJa()
    {
        return $this->obtainmentJa;
    }

    /**
     * Set obtainmentRu.
     *
     * @param string $obtainmentRu
     *
     * @return ItemTemplate
     */
    public function setObtainmentRu($obtainmentRu)
    {
        $this->obtainmentRu = $obtainmentRu;

        return $this;
    }

    /**
     * Get obtainmentRu.
     *
     * @return string
     */
    public function getObtainmentRu()
    {
        return $this->obtainmentRu;
    }

    /**
     * Set obtainment.
     *
     * @param string $obtainment
     * @param string $locale
     *
     * @return object
     */
    public function setObtainment($obtainment, $locale = 'fr')
    {
        switch ($locale) {
            case 'fr': $this->obtainmentFr = $obtainment; break;
            case 'en': $this->obtainmentEn = $obtainment; break;
            case 'de': $this->obtainmentDe = $obtainment; break;
            case 'es': $this->obtainmentEs = $obtainment; break;
            case 'it': $this->obtainmentIt = $obtainment; break;
            case 'pt': $this->obtainmentPt = $obtainment; break;
            case 'ja': $this->obtainmentJa = $obtainment; break;
            case 'ru': $this->obtainmentRu = $obtainment; break;
        }

        return $this;
    }

    /**
     * Get obtainment.
     *
     * @param string|array $locale
     *
     * @return string
     */
    public function getObtainment($locale = 'fr')
    {
        if (is_array($locale)) {
            foreach ($locale as $loc) {
                $obtainment = $this->getObtainment($loc);
                if ($obtainment !== null) {
                    return $obtainment;
                }
            }

            return;
        }
        switch ($locale) {
            case 'fr': return $this->obtainmentFr;
            case 'en': return $this->obtainmentEn;
            case 'de': return $this->obtainmentDe;
            case 'es': return $this->obtainmentEs;
            case 'it': return $this->obtainmentIt;
            case 'pt': return $this->obtainmentPt;
            case 'ja': return $this->obtainmentJa;
            case 'ru': return $this->obtainmentRu;
            default: return;
        }
    }

    /**
     * Set iconRelativePath.
     *
     * @param string $iconRelativePath
     *
     * @return ItemTemplate
     */
    public function setIconRelativePath($iconRelativePath)
    {
        $this->iconRelativePath = $iconRelativePath;

        return $this;
    }

    /**
     * Get iconRelativePath.
     *
     * @return string
     */
    public function getIconRelativePath()
    {
        return $this->iconRelativePath;
    }

    /**
     * Set iconId.
     *
     * @param int $iconId
     *
     * @return ItemTemplate
     */
    public function setIconId($iconId)
    {
        $this->iconId = $iconId;

        return $this;
    }

    /**
     * Get iconId.
     *
     * @return int
     */
    public function getIconId()
    {
        return $this->iconId;
    }

    /**
     * Set dominantColor.
     *
     * @param int $dominantColor
     *
     * @return ItemTemplate
     */
    public function setDominantColor($dominantColor)
    {
        $this->dominantColor = $dominantColor;

        return $this;
    }

    /**
     * Get dominantColor.
     *
     * @return int
     */
    public function getDominantColor()
    {
        return $this->dominantColor;
    }

    /**
     * Set criteria.
     *
     * @param string $criteria
     *
     * @return ItemTemplate
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * Get criteria.
     *
     * @return string
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Set level.
     *
     * @param int $level
     *
     * @return ItemTemplate
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set weight.
     *
     * @param int $weight
     *
     * @return ItemTemplate
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight.
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set tradeable.
     *
     * @param bool $tradeable
     *
     * @return ItemTemplate
     */
    public function setTradeable($tradeable)
    {
        $this->tradeable = $tradeable;

        return $this;
    }

    /**
     * Get tradeable.
     *
     * @return bool
     */
    public function getTradeable()
    {
        return $this->tradeable;
    }

    /**
     * Get tradeable.
     *
     * @return bool
     */
    public function isTradeable()
    {
        return $this->tradeable;
    }

    /**
     * Set npcPrice.
     *
     * @param int $npcPrice
     *
     * @return ItemTemplate
     */
    public function setNpcPrice($npcPrice)
    {
        $this->npcPrice = $npcPrice;

        return $this;
    }

    /**
     * Get npcPrice.
     *
     * @return int
     */
    public function getNpcPrice()
    {
        return $this->npcPrice;
    }

    /**
     * Add effects.
     *
     * @param ItemTemplateEffect $effects
     *
     * @return ItemTemplate
     */
    public function addEffect(ItemTemplateEffect $effects)
    {
        $this->effects[] = $effects;

        return $this;
    }

    /**
     * Remove effects.
     *
     * @param ItemTemplateEffect $effects
     *
     * @return ItemTemplate
     */
    public function removeEffect(ItemTemplateEffect $effects)
    {
        $this->effects->removeElement($effects);

        return $this;
    }

    /**
     * Get effects.
     *
     * @return Collection
     */
    public function getEffects()
    {
        return $this->effects;
    }

    /**
     * Add components.
     *
     * @param ItemComponent $components
     *
     * @return ItemTemplate
     */
    public function addComponent(ItemComponent $components)
    {
        $this->components[] = $components;

        return $this;
    }

    /**
     * Remove components.
     *
     * @param ItemComponent $components
     *
     * @return ItemTemplate
     */
    public function removeComponent(ItemComponent $components)
    {
        $this->components->removeElement($components);

        return $this;
    }

    /**
     * Get components.
     *
     * @return Collection
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * Add compounds.
     *
     * @param ItemComponent $compounds
     *
     * @return ItemTemplate
     */
    public function addCompound(ItemComponent $compounds)
    {
        $this->compounds[] = $compounds;

        return $this;
    }

    /**
     * Remove compounds.
     *
     * @param ItemComponent $compounds
     *
     * @return ItemTemplate
     */
    public function removeCompound(ItemComponent $compounds)
    {
        $this->compounds->removeElement($compounds);

        return $this;
    }

    /**
     * Get compounds.
     *
     * @return Collection
     */
    public function getCompounds()
    {
        return $this->compounds;
    }

    /**
     * Set visible.
     *
     * @param bool $visible
     *
     * @return ItemTemplate
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible.
     *
     * @return bool
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Get visible.
     *
     * @return bool
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * Set sticky.
     *
     * @param bool $sticky
     *
     * @return ItemTemplate
     */
    public function setSticky($sticky)
    {
        $this->sticky = $sticky;

        return $this;
    }

    /**
     * Get sticky.
     *
     * @return bool
     */
    public function getSticky()
    {
        return $this->sticky;
    }

    /**
     * Get sticky.
     *
     * @return bool
     */
    public function isSticky()
    {
        return $this->sticky;
    }

    /**
     * Set gatheringJob.
     *
     * @param Job $gatheringJob
     *
     * @return ItemTemplate
     */
    public function setGatheringJob(Job $gatheringJob = null)
    {
        $this->gatheringJob = $gatheringJob;

        return $this;
    }

    /**
     * Get gatheringJob.
     *
     * @return Job
     */
    public function getGatheringJob()
    {
        return $this->gatheringJob;
    }

    /**
     * Set gatheringJobMinLevel.
     *
     * @param int $gatheringJobMinLevel
     *
     * @return ItemTemplate
     */
    public function setGatheringJobMinLevel($gatheringJobMinLevel)
    {
        $this->gatheringJobMinLevel = $gatheringJobMinLevel;

        return $this;
    }

    /**
     * Get gatheringJobMinLevel.
     *
     * @return int
     */
    public function getGatheringJobMinLevel()
    {
        return $this->gatheringJobMinLevel;
    }

    /**
     * Set craftingJob.
     *
     * @param Job $craftingJob
     *
     * @return ItemTemplate
     */
    public function setCraftingJob(Job $craftingJob = null)
    {
        $this->craftingJob = $craftingJob;

        return $this;
    }

    /**
     * Get craftingJob.
     *
     * @return Job
     */
    public function getCraftingJob()
    {
        return $this->craftingJob;
    }

    /**
     * Add drops.
     *
     * @param MonsterDrop $drops
     *
     * @return ItemTemplate
     */
    public function addDrop(MonsterDrop $drops)
    {
        $this->drops[] = $drops;

        return $this;
    }

    /**
     * Remove drops.
     *
     * @param MonsterDrop $drops
     *
     * @return ItemTemplate
     */
    public function removeDrop(MonsterDrop $drops)
    {
        $this->drops->removeElement($drops);

        return $this;
    }

    /**
     * Get drops.
     *
     * @return Collection
     */
    public function getDrops()
    {
        return $this->drops;
    }

    public function __toString()
    {
        return $this->nameFr;
    }

    public function isPersonalized()
    {
        return false;
    }
    public function isEquipment()
    {
        return false;
    }
    public function isSkinned()
    {
        return false;
    }
    public function isWeapon()
    {
        return false;
    }
    public function isAnimal()
    {
        return false;
    }
    public function isPet()
    {
        return false;
    }
    public function isMount()
    {
        return false;
    }
    public function isUseable()
    {
        return false;
    }
    public function isCraft()
    {
        return false;
    }
    public function getClassId()
    {
        return 'item';
    }

    public function exportData($full = true, $locale = 'fr')
    {
        return $this->exportTimestampableData($full) + $this->exportSluggableData($full) + [
            'name' => $this->getName($locale),
            'type' => $this->type->exportData(false, $locale),
            'level' => $this->level,
            'class' => $this->getClassId(),
        ] + ($full ? [
            'description' => $this->getDescription($locale),
            'obtainment' => $this->getObtainment($locale),
            'iconRelativePath' => $this->iconRelativePath,
            'dominantColor' => $this->dominantColor,
            'criteria' => $this->criteria,
            'weight' => $this->weight,
            'tradeable' => $this->tradeable,
            'npcPrice' => $this->npcPrice,
            'effects' => array_map(function ($ent) use ($locale) { return $ent->exportData(false, $locale); }, $this->effects->toArray()),
            'release' => $this->release,
            'preliminary' => $this->preliminary,
            'deprecated' => $this->deprecated,
        ] : []);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        return false;
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/items';
    }

    public function preUpload()
    {
        if (null !== $this->file) {
            if (!empty($this->path)) {
                $this->pathToRemove = $this->path;
            }
            $this->path = UrlSafeBase64::encode($this->iconId).'.'.$this->file->guessExtension();
        }
    }

    public function upload()
    {
        if (null === $this->file) {
            return;
        }
        if (!empty($this->pathToRemove)) {
            unlink($this->pathToRemove);
            $this->pathToRemove = null;
        }
        system('/usr/bin/convert '.escapeshellarg(strval($this->file)).' -resize 131x131 '.escapeshellarg($this->getUploadRootDir().'/'.$this->path));

        unset($this->file);
    }

    public function setPath($path)
    {
        if ($this->path == $path) {
            return;
        }
        $this->removeUpload();
        $this->path = $path;
    }
}
