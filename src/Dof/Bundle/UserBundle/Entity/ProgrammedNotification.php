<?php

namespace Dof\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\OwnableInterface;
use Dof\Bundle\UserBundle\OwnableTrait;
use XN\Metadata\SimpleLazyFieldTrait;

/**
 * ProgrammedNotification.
 *
 * @ORM\Table(name="dof_user_programmed_notifications")
 * @ORM\Entity(repositoryClass="Dof\Bundle\UserBundle\Entity\ProgrammedNotificationRepository")
 */
class ProgrammedNotification implements IdentifiableInterface, OwnableInterface
{
    use TimestampableTrait, OwnableTrait, SimpleLazyFieldTrait;

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
     * @param string $type
     *
     * @return ProgrammedNotification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return ProgrammedNotification
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
