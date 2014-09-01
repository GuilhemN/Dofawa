<?php

namespace Dof\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;

//Traduction Titre/Description
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;

use Dof\MainBundle\Entity\BadgeLevel;

/**
 * Badge
 *
 * @ORM\Table(name="dof_badges")
 * @ORM\Entity(repositoryClass="Dof\MainBundle\Entity\BadgeRepository")
 */
class Badge implements IdentifiableInterface, TimestampableInterface
{
    use TimestampableTrait, LocalizedNameTrait, LocalizedDescriptionTrait;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Dof\MainBundle\Entity\BadgeLevel", mappedBy="badge")
     */
    private $levels;

    public function __construct()
    {
        $this->levels = new ArrayCollection();
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
     * Set slug
     *
     * @param string $slug
     * @return Badge
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }


    /**
     * Add levels
     *
     * @param BadgeLevel $levels
     * @return Badge
     */
    public function addLevel(BadgeLevel $levels)
    {
        $this->levels[] = $levels;

        return $this;
    }

    /**
     * Remove levels
     *
     * @param BadgeLevel $levels
     * @return Badge
     */
    public function removeLevel(BadgeLevel $levels)
    {
        $this->levels->removeElement($levels);

        return $this;
    }

    /**
     * Get levels
     *
     * @return Collection
     */
    public function getLevels()
    {
        return $this->levels;
    }

    public function __toString(){
        return $this->getName();
    }
}
