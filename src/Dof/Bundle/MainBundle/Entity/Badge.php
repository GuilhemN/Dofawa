<?php

namespace Dof\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
//Traduction Titre/Description
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\UserBundle\Entity\Badge as UserBadge;

/**
 * Badge.
 *
 * @ORM\Table(name="dof_badges")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MainBundle\Entity\BadgeRepository")
 */
class Badge implements IdentifiableInterface, LocalizedNameInterface
{
    use TimestampableTrait, LocalizedNameTrait;
    /**
     * @var int
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
     * @ORM\OneToMany(targetEntity="Dof\Bundle\MainBundle\Entity\BadgeLevel", mappedBy="badge")
     */
    private $levels;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Dof\Bundle\UserBundle\Entity\Badge", mappedBy="badge")
     */
    private $userBadges;

    public function __construct()
    {
        $this->levels = new ArrayCollection();
        $this->userBadges = new ArrayCollection();
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
     * Set slug.
     *
     * @param string $slug
     *
     * @return Badge
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add levels.
     *
     * @param BadgeLevel $levels
     *
     * @return Badge
     */
    public function addLevel(BadgeLevel $levels)
    {
        $this->levels[] = $levels;

        return $this;
    }

    /**
     * Remove levels.
     *
     * @param BadgeLevel $levels
     *
     * @return Badge
     */
    public function removeLevel(BadgeLevel $levels)
    {
        $this->levels->removeElement($levels);

        return $this;
    }

    /**
     * Get levels.
     *
     * @return Collection
     */
    public function getLevels()
    {
        return $this->levels;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Add userBadges.
     *
     * @param UserBadge $userBadges
     *
     * @return Badge
     */
    public function addUserBadge(UserBadge $userBadges)
    {
        $this->userBadges[] = $userBadges;

        return $this;
    }

    /**
     * Remove userBadges.
     *
     * @param UserBadge $userBadges
     *
     * @return Badge
     */
    public function removeUserBadge(UserBadge $userBadges)
    {
        $this->userBadges->removeElement($userBadges);

        return $this;
    }

    /**
     * Get userBadges.
     *
     * @return Collection
     */
    public function getUserBadges()
    {
        return $this->userBadges;
    }
}
