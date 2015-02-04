<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

use XN\Common\UrlSafeBase64;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;
use Dof\ItemBundle\ReleaseBoundTrait;

use XN\Metadata\FileLightTrait;
use XN\Metadata\FileInterface;

use Dof\CharactersBundle\Entity\Breed;
use Dof\MonsterBundle\Entity\Monster;
/**
 * Spell
 *
 * @ORM\Table(name="dof_spells")
 * @ORM\Entity(repositoryClass="SpellRepository")
 */
class Spell implements IdentifiableInterface, TimestampableInterface, SluggableInterface, LocalizedNameInterface, FileInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, LocalizedDescriptionTrait, ReleaseBoundTrait, FileLightTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="iconId", type="integer")
     */
    private $iconId;

    /**
     * @var integer
     *
     * @ORM\Column(name="typeId", type="integer")
     */
    private $typeId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="publiclyVisible", type="boolean")
     */
    private $publiclyVisible;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="SpellRank", mappedBy="spell")
     * @ORM\OrderBy({ "rank" = "ASC" })
     */
    private $ranks;

    /**
    * @ORM\ManyToMany(targetEntity="Dof\CharactersBundle\Entity\Breed", inversedBy="spells")
    * @ORM\JoinTable(name="dof_breed_spells")
    */
    private $breeds;

    /**
    * @ORM\ManyToMany(targetEntity="Dof\MonsterBundle\Entity\Monster", inversedBy="spells")
    * @ORM\JoinTable(name="dof_monster_spells")
    */
    private $monsters;
    
	/**
    * @ORM\ManyToMany(targetEntity="Dof\MonsterBundle\Entity\Monster", inversedBy="passiveSpells")
    * @ORM\JoinTable(name="dof_monster_passive_spells")
    */
    private $passiveOfMonsters;

    /**
    * @Assert\Image(
    *     maxSize = "1024k",
    *     minWidth = 55,
    *     maxWidth = 55,
    *     minHeight = 55,
    *     maxHeight = 55,
    *     mimeTypesMessage = "Choisissez un fichier image valide.")
    */
    private $file;

    public function __construct()
    {
        $this->ranks = new ArrayCollection();
        $this->breeds = new ArrayCollection();
        $this->monsters = new ArrayCollection();
        $this->passiveOfMonsters = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Spell
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
     * Set iconId
     *
     * @param integer $iconId
     * @return Spell
     */
    public function setIconId($iconId)
    {
        $this->iconId = $iconId;

        return $this;
    }

    /**
     * Get iconId
     *
     * @return integer
     */
    public function getIconId()
    {
        return $this->iconId;
    }

    /**
     * Set typeId
     *
     * @param integer $typeId
     * @return Spell
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get typeId
     *
     * @return integer
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set publiclyVisible
     *
     * @param boolean $publiclyVisible
     * @return Spell
     */
    public function setPubliclyVisible($publiclyVisible)
    {
        $this->publiclyVisible = $publiclyVisible;

        return $this;
    }

    /**
     * Get publiclyVisible
     *
     * @return boolean
     */
    public function getPubliclyVisible()
    {
        return $this->publiclyVisible;
    }

    /**
     * Get publiclyVisible
     *
     * @return boolean
     */
    public function isPubliclyVisible()
    {
        return $this->publiclyVisible;
    }

    /**
     * Add ranks
     *
     * @param SpellRank $ranks
     * @return Spell
     */
    public function addRank(SpellRank $ranks)
    {
        $this->ranks[] = $ranks;

        return $this;
    }

    /**
     * Remove ranks
     *
     * @param SpellRank $ranks
     * @return Spell
     */
    public function removeRank(SpellRank $ranks)
    {
        $this->ranks->removeElement($ranks);

        return $this;
    }

    /**
     * Get ranks
     *
     * @return Collection
     */
    public function getRanks()
    {
        return $this->ranks;
    }

    /**
    * Add breeds
    *
    * @param Breed $breeds
    * @return Spell
    */
    public function addBreed(Breed $breeds)
    {
        $this->breeds[] = $breeds;

        return $this;
    }

    /**
    * Remove breeds
    *
    * @param Breed $breeds
    * @return Spell
    */
    public function removeBreed(Breed $breeds)
    {
        $this->breeds->removeElement($breeds);

        return $this;
    }

    /**
    * Get breeds
    *
    * @return Collection
    */
    public function getBreeds()
    {
        return $this->breeds;
    }

    /**
    * Add monsters
    *
    * @param Monster $monsters
    * @return Spell
    */
    public function addMonster(Monster $monsters)
    {
        $this->monsters[] = $monsters;

        return $this;
    }

    /**
    * Remove monsters
    *
    * @param Monster $monsters
    * @return Spell
    */
    public function removeMonster(Monster $monsters)
    {
        $this->monsters->removeElement($monsters);

        return $this;
    }

    /**
    * Get monsters
    *
    * @return Collection
    */
    public function getMonsters()
    {
        return $this->monsters;
    }

    /**
     * Add passiveOfMonsters
     *
     * @param Monster $passiveOfMonsters
     * @return Spell
     */
    public function addPassiveOfMonster(Monster $passiveOfMonsters)
    {
        $this->passiveOfMonsters[] = $passiveOfMonsters;

        return $this;
    }

    /**
     * Remove passiveOfMonsters
     *
     * @param Monster $passiveOfMonsters
     * @return Spell
     */
    public function removePassiveOfMonster(Monster $passiveOfMonsters)
    {
        $this->passiveOfMonsters->removeElement($passiveOfMonsters);

        return $this;
    }

    /**
     * Get passiveOfMonsters
     *
     * @return Collection
     */
    public function getPassiveOfMonsters()
    {
        return $this->passiveOfMonsters;
    }

    public function __toString()
    {
        return $this->getName();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/spells';
    }

    public function preUpload()
    {
        if (null !== $this->file) {
            if(!empty($this->path))
            $this->pathToRemove = $this->path;
            $this->path = UrlSafeBase64::encode($this->iconId) . '.' . $this->file->guessExtension();
        }
    }
}
