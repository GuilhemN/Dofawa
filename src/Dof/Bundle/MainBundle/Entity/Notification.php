<?php

namespace Dof\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\OwnableInterface;
use Dof\Bundle\UserBundle\OwnableTrait;
use XN\Metadata\SimpleLazyFieldTrait;

/**
 * Notification.
 *
 * @ORM\Table(name="dof_notifications")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MainBundle\Entity\NotificationRepository")
 */
class Notification implements IdentifiableInterface, OwnableInterface
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
     * @var bool
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead = false;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    private $valid;

    public function __construct()
    {
        $this->valid = true;
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
     * @param string $type
     *
     * @return Notification
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
     * Set isRead.
     *
     * @param bool $isRead
     *
     * @return Notification
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead.
     *
     * @return bool
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    public function isRead()
    {
        return $this->isRead;
    }

    /**
     * Set message.
     *
     * @param string $message
     *
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return Notification
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    public function getValid()
    {
        return $this->isValid();
    }

    public function isValid()
    {
        return ($this->getEntity() === null && $this->getClass() === null)
            or ($this->getEntity() !== null && $this->getClass() !== null);
    }
}
