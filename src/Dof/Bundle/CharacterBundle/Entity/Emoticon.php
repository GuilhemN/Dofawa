<?php

namespace Dof\Bundle\CharacterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * Emoticon.
 *
 * @ORM\Table(name="dof_emoticons")
 * @ORM\Entity(repositoryClass="Dof\Bundle\CharacterBundle\Entity\EmoticonRepository")
 */
class Emoticon implements IdentifiableInterface
{
    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="_order", type="integer")
     */
    private $order;

    /**
     * @var bool
     *
     * @ORM\Column(name="aura", type="boolean")
     */
    private $aura;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="shortcut", type="string", length=255, nullable=true)
     */
    private $shortcut;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Emoticon
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set order.
     *
     * @param int $order
     *
     * @return Emoticon
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set aura.
     *
     * @param bool $aura
     *
     * @return Emoticon
     */
    public function setAura($aura)
    {
        $this->aura = $aura;

        return $this;
    }

    /**
     * Get aura.
     *
     * @return bool
     */
    public function getAura()
    {
        return $this->aura;
    }

    /**
     * Set shortcut.
     *
     * @param string $shortcut
     *
     * @return Emoticon
     */
    public function setShortcut($shortcut)
    {
        $this->shortcut = $shortcut;

        return $this;
    }

    /**
     * Get shortcutName.
     *
     * @return string
     */
    public function getShortcut()
    {
        return $this->shortcutName;
    }

    public function __toString()
    {
        return $this->name;
    }
}
