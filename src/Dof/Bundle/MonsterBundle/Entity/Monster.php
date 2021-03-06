<?php

namespace Dof\Bundle\MonsterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use XN\Common\UrlSafeBase64;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;
use XN\Metadata\FileInterface;
use XN\Metadata\FileLightTrait;
use Dof\Bundle\CharacterBundle\Entity\Spell;
use Dof\Bundle\MapBundle\Entity\SubArea;

/**
 * Monster.
 *
 * @ORM\Table(name="dof_monsters")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MonsterBundle\Entity\MonsterRepository")
 */
class Monster implements IdentifiableInterface, LocalizedNameInterface, FileInterface
{
    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, ReleaseBoundTrait, FileLightTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var MonsterRace
     *
     * @ORM\ManyToOne(targetEntity="MonsterRace", inversedBy="monsters")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $race;

    /**
     * @ORM\OneToMany(targetEntity="MonsterGrade", mappedBy="monster")
     */
    private $grades;

    /**
     * @ORM\OneToMany(targetEntity="MonsterDrop", mappedBy="monster")
     */
    private $drops;

    /**
     * @var Monster
     *
     * @ORM\OneToOne(targetEntity="Monster", inversedBy="normalMonster")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $archMonster;

    /**
     * @var Monster
     *
     * @ORM\OneToOne(targetEntity="Monster", mappedBy="archMonster")
     */
    private $normalMonster;

    /**
     * @ORM\ManyToMany(targetEntity="Dof\Bundle\CharacterBundle\Entity\Spell", mappedBy="monsters")
     */
    private $spells;

    /**
     * @ORM\ManyToMany(targetEntity="Dof\Bundle\CharacterBundle\Entity\Spell", mappedBy="passiveOfMonsters")
     */
    private $passiveSpells;

    /**
     * @ORM\ManyToMany(targetEntity="Dof\Bundle\MapBundle\Entity\SubArea", mappedBy="monsters")
     */
    private $subAreas;

    /**
     * @ORM\ManyToMany(targetEntity="Dungeon")
     */
    private $dungeons;

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * @var bool
     *
     * @ORM\Column(name="use_summon_slot", type="boolean")
     */
    private $useSummonSlot;

    /**
     * @var bool
     *
     * @ORM\Column(name="use_bomb_slot", type="boolean")
     */
    private $useBombSlot;

    /**
     * @var bool
     *
     * @ORM\Column(name="can_play", type="boolean")
     */
    private $canPlay;

    /**
     * @var bool
     *
     * @ORM\Column(name="can_tackle", type="boolean")
     */
    private $canTackle;

    /**
     * @var bool
     *
     * @ORM\Column(name="boss", type="boolean")
     */
    private $boss;

    /**
     * @var bool
     *
     * @ORM\Column(name="mini_boss", type="boolean")
     */
    private $miniBoss;

    /**
     * @var bool
     *
     * @ORM\Column(name="can_be_pushed", type="boolean")
     */
    private $canBePushed;

    /**
     * @var string
     *
     * @ORM\Column(name="look", type="string", length=255)
     */
    private $look;

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

    /**
     * @var string
     *
     * @Groups({"item"})
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
        $this->drops = new ArrayCollection();
        $this->spells = new ArrayCollection();
        $this->passiveSpells = new ArrayCollection();
        $this->subAreas = new ArrayCollection();
        $this->dungeons = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Monster
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
     * Set race.
     *
     * @param MonsterRace $race
     *
     * @return Monster
     */
    public function setRace(MonsterRace $race)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race.
     *
     * @return MonsterRace
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * Add grades.
     *
     * @param MonsterGrade $grades
     *
     * @return Monster
     */
    public function addGrade(MonsterGrade $grades)
    {
        $this->grades[] = $grades;

        return $this;
    }

    /**
     * Remove grades.
     *
     * @param MonsterGrade $grades
     *
     * @return Monster
     */
    public function removeGrade(MonsterGrade $grades)
    {
        $this->grades->removeElement($grades);

        return $this;
    }

    /**
     * Get grades.
     *
     * @return Collection
     */
    public function getGrades()
    {
        return $this->grades;
    }

    public function getMinGrade()
    {
        $min = null;
        $minGrade = null;
        foreach ($this->grades as $grade) {
            if ($min === null or $min > $grade->getGrade()) {
                $min = $grade->getGrade();
                $minGrade = $grade;
            }
        }

        return $minGrade;
    }

    public function getMaxGrade()
    {
        $max = null;
        $maxGrade = null;
        foreach ($this->grades as $grade) {
            if ($max === null or $max < $grade->getGrade()) {
                $max = $grade->getGrade();
                $maxGrade = $grade;
            }
        }

        return $maxGrade;
    }

    /**
     * Add drops.
     *
     * @param MonsterDrop $drops
     *
     * @return Monster
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
     * @return Monster
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

    /**
     * Get drops.
     *
     * @return Collection
     */
    public function getNormalDrops()
    {
        return array_filter($this->drops->toArray(), function ($v) {
            return !$v->getHasCriteria();
        });
    }

    /**
     * Get drops.
     *
     * @return Collection
     */
    public function getConditionedDrops()
    {
        return array_filter($this->drops->toArray(), function ($v) {
            return $v->getHasCriteria();
        });
    }

    /**
     * Add spells.
     *
     * @param Spell $spells
     *
     * @return Monster
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
     * @return Monster
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

    /**
     * Add passiveSpells.
     *
     * @param Spell $passiveSpells
     *
     * @return Monster
     */
    public function addPassiveSpell(Spell $passiveSpells)
    {
        $this->passiveSpells[] = $passiveSpells;

        return $this;
    }

    /**
     * Remove passiveSpells.
     *
     * @param Spell $passiveSpells
     *
     * @return Monster
     */
    public function removePassiveSpell(Spell $passiveSpells)
    {
        $this->passiveSpells->removeElement($passiveSpells);

        return $this;
    }

    /**
     * Get passiveSpells.
     *
     * @return Collection
     */
    public function getPassiveSpells()
    {
        return $this->passiveSpells;
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

    /**
     * Add subAreas.
     *
     * @param SubArea $subAreas
     *
     * @return Monster
     */
    public function addSubArea(SubArea $subAreas)
    {
        $this->subAreas[] = $subAreas;

        return $this;
    }

    /**
     * Remove subAreas.
     *
     * @param SubArea $subAreas
     *
     * @return Monster
     */
    public function removeSubArea(SubArea $subAreas)
    {
        $this->subAreas->removeElement($subAreas);

        return $this;
    }

    /**
     * Get subAreas.
     *
     * @return Collection
     */
    public function getSubAreas()
    {
        return $this->subAreas;
    }
    /**
     * Add dungeons.
     *
     * @param Dungeon $dungeons
     *
     * @return Monster
     */
    public function addDungeon(Dungeon $dungeons)
    {
        $this->dungeons[] = $dungeons;

        return $this;
    }

    /**
     * Remove dungeons.
     *
     * @param Dungeon $dungeons
     *
     * @return Monster
     */
    public function removeDungeon(Dungeon $dungeons)
    {
        $this->dungeons->removeElement($dungeons);

        return $this;
    }

    /**
     * Get dungeons.
     *
     * @return Collection
     */
    public function getDungeons()
    {
        return $this->dungeons;
    }

    /**
     * Set visible.
     *
     * @param bool $visible
     *
     * @return Monster
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
     * Set useSummonSlot.
     *
     * @param bool $useSummonSlot
     *
     * @return Monster
     */
    public function setUseSummonSlot($useSummonSlot)
    {
        $this->useSummonSlot = $useSummonSlot;

        return $this;
    }

    /**
     * Get useSummonSlot.
     *
     * @return bool
     */
    public function getUseSummonSlot()
    {
        return $this->useSummonSlot;
    }

    /**
     * Get useSummonSlot.
     *
     * @return bool
     */
    public function isUseSummonSlot()
    {
        return $this->useSummonSlot;
    }

    /**
     * Set useBombSlot.
     *
     * @param bool $useBombSlot
     *
     * @return Monster
     */
    public function setUseBombSlot($useBombSlot)
    {
        $this->useBombSlot = $useBombSlot;

        return $this;
    }

    /**
     * Get useBombSlot.
     *
     * @return bool
     */
    public function getUseBombSlot()
    {
        return $this->useBombSlot;
    }

    /**
     * Get useBombSlot.
     *
     * @return bool
     */
    public function isUseBombSlot()
    {
        return $this->useBombSlot;
    }

    /**
     * Set canPlay.
     *
     * @param bool $canPlay
     *
     * @return Monster
     */
    public function setCanPlay($canPlay)
    {
        $this->canPlay = $canPlay;

        return $this;
    }

    /**
     * Get canPlay.
     *
     * @return bool
     */
    public function getCanPlay()
    {
        return $this->canPlay;
    }

    /**
     * Get canPlay.
     *
     * @return bool
     */
    public function isCanPlay()
    {
        return $this->canPlay;
    }

    /**
     * Set canTackle.
     *
     * @param bool $canTackle
     *
     * @return Monster
     */
    public function setCanTackle($canTackle)
    {
        $this->canTackle = $canTackle;

        return $this;
    }

    /**
     * Get canTackle.
     *
     * @return bool
     */
    public function getCanTackle()
    {
        return $this->canTackle;
    }

    /**
     * Get canTackle.
     *
     * @return bool
     */
    public function isCanTackle()
    {
        return $this->canTackle;
    }

    /**
     * Set boss.
     *
     * @param bool $boss
     *
     * @return Monster
     */
    public function setBoss($boss)
    {
        $this->boss = $boss;

        return $this;
    }

    /**
     * Get boss.
     *
     * @return bool
     */
    public function getBoss()
    {
        return $this->boss;
    }

    /**
     * Get boss.
     *
     * @return bool
     */
    public function isBoss()
    {
        return $this->boss;
    }

    /**
     * Set miniBoss.
     *
     * @param bool $miniBoss
     *
     * @return Monster
     */
    public function setMiniBoss($miniBoss)
    {
        $this->miniBoss = $miniBoss;

        return $this;
    }

    /**
     * Get miniBoss.
     *
     * @return bool
     */
    public function getMiniBoss()
    {
        return $this->miniBoss;
    }

    /**
     * Get miniBoss.
     *
     * @return bool
     */
    public function isMiniBoss()
    {
        return $this->miniBoss;
    }

    /**
     * Set canBePushed.
     *
     * @param bool $canBePushed
     *
     * @return Monster
     */
    public function setCanBePushed($canBePushed)
    {
        $this->canBePushed = $canBePushed;

        return $this;
    }

    /**
     * Get canBePushed.
     *
     * @return bool
     */
    public function getCanBePushed()
    {
        return $this->canBePushed;
    }

    /**
     * Get canBePushed.
     *
     * @return bool
     */
    public function isCanBePushed()
    {
        return $this->canBePushed;
    }

    /**
     * Set archMonster.
     *
     * @param Monster $archMonster
     *
     * @return Monster
     */
    public function setArchMonster(Monster $archMonster = null)
    {
        $this->archMonster = $archMonster;

        return $this;
    }

    /**
     * Get archMonster.
     *
     * @return Monster
     */
    public function getArchMonster()
    {
        return $this->archMonster;
    }

    /**
     * Set normalMonster.
     *
     * @param Monster $normalMonster
     *
     * @return Monster
     */
    public function setNormalMonster(Monster $normalMonster = null)
    {
        $this->normalMonster = $normalMonster;

        return $this;
    }

    /**
     * Get normalMonster.
     *
     * @return Monster
     */
    public function getNormalMonster()
    {
        return $this->normalMonster;
    }
    /**
     * Set look.
     *
     * @param string $look
     *
     * @return Monster
     */
    public function setLook($look)
    {
        $this->look = $look;

        return $this;
    }

    /**
     * Get look.
     *
     * @return string
     */
    public function getLook()
    {
        return $this->look;
    }

    public function __toString()
    {
        return $this->name;
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/monsters';
    }

    public function preUpload()
    {
        if (null !== $this->file) {
            if (!empty($this->path)) {
                $this->pathToRemove = $this->path;
            }
            $this->path = UrlSafeBase64::encode($this->look).'.'.$this->file->guessExtension();
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
        system('/usr/bin/convert '.escapeshellarg(strval($this->file)).' -resize 131x180 '.escapeshellarg($this->getUploadRootDir().'/'.$this->path));

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
