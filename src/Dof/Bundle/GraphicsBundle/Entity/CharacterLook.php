<?php

namespace Dof\Bundle\GraphicsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\Metadata\OwnableInterface;
use Dof\Bundle\UserBundle\OwnableTrait;
use Dof\Bundle\GraphicsBundle\BasicPCLook;

/**
 * CharacterLook.
 *
 * @ORM\Table(name="dof_character_looks")
 * @ORM\Entity(repositoryClass="CharacterLookRepository")
 */
class CharacterLook extends BasicPCLook implements IdentifiableInterface, OwnableInterface
{
    /**
     * @var int
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
     * @var bool
     *
     * @ORM\Column(name="publiclyVisible", type="boolean")
     */
    private $publiclyVisible;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

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
     * Set name.
     *
     * @param string $name
     *
     * @return CharacterLook
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set publiclyVisible.
     *
     * @param bool $publiclyVisible
     *
     * @return CharacterLook
     */
    public function setPubliclyVisible($publiclyVisible)
    {
        $this->publiclyVisible = $publiclyVisible;

        return $this;
    }

    /**
     * Get publiclyVisible.
     *
     * @return bool
     */
    public function getPubliclyVisible()
    {
        return $this->publiclyVisible;
    }

    /**
     * Get publiclyVisible.
     *
     * @return bool
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
