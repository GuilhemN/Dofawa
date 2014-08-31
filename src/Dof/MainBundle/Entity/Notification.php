<?php

namespace Dof\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

/**
 * Notification
 *
 * @ORM\Table(name="dof_notifications")
 * @ORM\Entity(repositoryClass="Dof\MainBundle\Entity\NotificationRepository")
 */
class Notification implements IdentifiableInterface, TimestampableInterface, OwnableInterface
{
    use TimestampableTrait, OwnableTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Dof\MainBundle\NotificationType
     * @var string
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="translate_string", type="string", length=255)
     */
    private $translateString;

    /**
     * @var array
     *
     * @ORM\Column(name="translate_params", type="json_array")
     */
    private $translateParams;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var array
     *
     * @ORM\Column(name="params", type="json_array")
     */
    private $params;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead;


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
     * @param integer $type
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set translateString
     *
     * @param string $translateString
     * @return Notification
     */
    public function setTranslateString($translateString)
    {
        $this->translateString = $translateString;

        return $this;
    }

    /**
     * Get translateString
     *
     * @return string
     */
    public function getTranslateString()
    {
        return $this->translateString;
    }
    /**
     * Set translateParams
     *
     * @param array $translateParams
     * @return Notification
     */
    public function setTranslateParams($translateParams)
    {
        $this->translateParams = $translateParams;

        return $this;
    }

    /**
     * Get translateParams
     *
     * @return array
     */
    public function getTranslateParams()
    {
        return $this->translateParams;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Notification
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set params
     *
     * @param array $params
     * @return Notification
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set isRead
     *
     * @param boolean $isRead
     * @return Notification
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }
}
