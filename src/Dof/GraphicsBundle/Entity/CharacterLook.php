<?php

namespace Dof\GraphicsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;
use XN\Metadata\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

use Dof\GraphicsBundle\BasicPCLook;

/**
 * CharacterLook
 *
 * @ORM\Table(name="dof_character_looks")
 * @ORM\Entity(repositoryClass="CharacterLookRepository")
 */
class CharacterLook extends BasicPCLook implements IdentifiableInterface, TimestampableInterface, SluggableInterface, OwnableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    use TimestampableTrait, SluggableTrait, OwnableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="publiclyVisible", type="boolean")
     */
    private $publiclyVisible;

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
     * Set name
     *
     * @param string $name
     * @return CharacterLook
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set publiclyVisible
     *
     * @param boolean $publiclyVisible
     * @return CharacterLook
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

    public function __toString()
    {
        return $this->name;
    }
}
