<?php

namespace Dof\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\MainBundle\Entity\Badge as BaseBadge;

/**
 * Badge
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Dof\UserBundle\Entity\BadgeRepository")
 */
class Badge
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\MainBundle\Entity\Badge")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $badge;


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
     * Set count
     *
     * @param integer $count
     * @return Badge
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }
    
    /**
     * Set badge
     *
     * @param BaseBadge $badge
     * @return Badge
     */
    public function setBadge(BaseBadge $badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Get badge
     *
     * @return BaseBadge
     */
    public function getBadge()
    {
        return $this->badge;
    }
}
