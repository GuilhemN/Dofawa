<?php

namespace Dof\MonsterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use XN\Common\UrlSafeBase64;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;
use XN\Metadata\FileInterface;
use XN\Metadata\FileLightTrait;

use Dof\CharactersBundle\Entity\Spell;

/**
 * Monster
 *
 * @ORM\Table(name="dof_monsters")
 * @ORM\Entity(repositoryClass="Dof\MonsterBundle\Entity\MonsterRepository")
 */
class Monster implements IdentifiableInterface, TimestampableInterface, SluggableInterface, LocalizedNameInterface, FileInterface
{
    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, ReleaseBoundTrait, FileLightTrait;

    /**
     * @var integer
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
    * @ORM\JoinColumn(nullable=true)
    */
    private $grades;

    /**
    * @ORM\OneToMany(targetEntity="MonsterDrop", mappedBy="monster")
    * @ORM\JoinColumn(nullable=true)
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
    * @ORM\ManyToMany(targetEntity="Dof\CharactersBundle\Entity\Spell", mappedBy="monsters")
    */
    private $spells;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

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

    public function __construct()
    {
        $this->grades = new ArrayCollection();
        $this->drops = new ArrayCollection();
        $this->spells = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Monster
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
    * Set race
    *
    * @param MonsterRace $race
    * @return Monster
    */
    public function setRace(MonsterRace $race)
    {
        $this->race = $race;

        return $this;
    }

    /**
    * Get race
    *
    * @return MonsterRace
    */
    public function getRace()
    {
        return $this->race;
    }

    /**
    * Add grades
    *
    * @param MonsterGrade $grades
    * @return Monster
    */
    public function addGrade(MonsterGrade $grades)
    {
        $this->grades[] = $grades;

        return $this;
    }

    /**
    * Remove grades
    *
    * @param MonsterGrade $grades
    * @return Monster
    */
    public function removeGrade(MonsterGrade $grades)
    {
        $this->grades->removeElement($grades);

        return $this;
    }

    /**
    * Get grades
    *
    * @return Collection
    */
    public function getGrades()
    {
        return $this->grades;
    }

    public function getMinGrade(){
        $min = null; $minGrade = null;
        foreach($this->grades as $grade)
            if($min === null or $min > $grade->getGrade()){
                $min = $grade->getGrade();
                $minGrade = $grade;
            }

        return $minGrade;
    }

    public function getMaxGrade(){
        $max = null; $maxGrade = null;
        foreach($this->grades as $grade)
            if($max === null or $max < $grade->getGrade()){
                $max = $grade->getGrade();
                $maxGrade = $grade;
            }

        return $maxGrade;
    }

    /**
    * Add drops
    *
    * @param MonsterDrop $drops
    * @return Monster
    */
    public function addDrop(MonsterDrop $drops)
    {
        $this->drops[] = $drops;

        return $this;
    }

    /**
    * Remove drops
    *
    * @param MonsterDrop $drops
    * @return Monster
    */
    public function removeDrop(MonsterDrop $drops)
    {
        $this->drops->removeElement($drops);

        return $this;
    }

    /**
    * Get drops
    *
    * @return Collection
    */
    public function getDrops()
    {
        return $this->drops;
    }

    /**
    * Add spells
    *
    * @param Spell $spells
    * @return Monster
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
    * @return Monster
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
        $spells = $this->spells->toArray();
        usort($spells, function($a, $b){
            $aLevel = $a->getRanks()[0]->getObtainmentLevel();
            $bLevel = $b->getRanks()[0]->getObtainmentLevel();
            return $aLevel - $bLevel;
        });
        return $spells;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return Monster
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
    * Set archMonster
    *
    * @param Monster $archMonster
    * @return Monster
    */
    public function setArchMonster(Monster $archMonster = null)
    {
        $this->archMonster = $archMonster;

        return $this;
    }

    /**
    * Get archMonster
    *
    * @return Monster
    */
    public function getArchMonster()
    {
        return $this->archMonster;
    }

    /**
    * Set normalMonster
    *
    * @param Monster $normalMonster
    * @return Monster
    */
    public function setNormalMonster(Monster $normalMonster = null)
    {
        $this->normalMonster = $normalMonster;

        return $this;
    }

    /**
    * Get normalMonster
    *
    * @return Monster
    */
    public function getNormalMonster()
    {
        return $this->normalMonster;
    }
    /**
    * Set look
    *
    * @param string $look
    * @return Monster
    */
    public function setLook($look)
    {
        $this->look = $look;

        return $this;
    }

    /**
    * Get look
    *
    * @return string
    */
    public function getLook()
    {
        return $this->look;
    }

    public function __toString()
    {
        return $this->nameFr;
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
            if(!empty($this->path))
            $this->pathToRemove = $this->path;
            $this->path = UrlSafeBase64::encode($this->look) . '.' . $this->file->guessExtension();
        }
    }

    public function upload()
    {
        if (null === $this->file) {
            return;
        }
        if(!empty($this->pathToRemove)){
            unlink($this->pathToRemove);
            $this->pathToRemove = null;
        }
        system("/usr/bin/convert " . escapeshellarg(strval($this->file)) . " -resize 131x180 " . escapeshellarg($this->getUploadRootDir() . '/' . $this->path));

        unset($this->file);
    }

    public function setPath($path){
        if($this->path == $path)
            return;
        $this->removeUpload();
        $this->path = $path;
    }
}
