<?php

namespace Dof\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\OwnableInterface;
use Dof\Bundle\UserBundle\OwnableTrait;
use Dof\Bundle\MainBundle\Entity\Badge as BaseBadge;

/**
 * Badge.
 *
 * @ORM\Table(name="dof_user_badges")
 * @ORM\Entity(repositoryClass="Dof\Bundle\UserBundle\Entity\BadgeRepository")
 */
class Badge implements IdentifiableInterface, OwnableInterface
{
    use TimestampableTrait, OwnableTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\MainBundle\Entity\Badge", inversedBy="userBadges")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $badge;

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
     * Set count.
     *
     * @param int $count
     *
     * @return Badge
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set badge.
     *
     * @param BaseBadge $badge
     *
     * @return Badge
     */
    public function setBadge(BaseBadge $badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Get badge.
     *
     * @return BaseBadge
     */
    public function getBadge()
    {
        return $this->badge;
    }
}
