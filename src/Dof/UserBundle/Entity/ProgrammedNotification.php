<?php

namespace Dof\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\OwnableInterface;
use Dof\UserBundle\OwnableTrait;
use XN\Metadata\SimpleLazyFieldTrait;

/**
 * ProgrammedNotification
 *
 * @ORM\Table(name="dof_user_programmed_notifications")
 * @ORM\Entity(repositoryClass="Dof\UserBundle\Entity\ProgrammedNotificationRepository")
 */
class ProgrammedNotification implements IdentifiableInterface, TimestampableInterface, OwnableInterface
{
    use TimestampableTrait, OwnableTrait, SimpleLazyFieldTrait;

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
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="created", type="boolean")
     */
    private $created;


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
     * Set type
     *
     * @param string $type
     * @return ProgrammedNotification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return ProgrammedNotification
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set created
     *
     * @param boolean $created
     * @return ProgrammedNotification
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return boolean
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get created
     *
     * @return boolean
     */
    public function isCreated()
    {
        return $this->created;
    }
}
