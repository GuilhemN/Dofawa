<?php

namespace Dof\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;

/**
 * Server.
 *
 * @ORM\Table("dof_servers")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MainBundle\Entity\ServerRepository")
 */
class Server implements IdentifiableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use SluggableTrait, LocalizedNameTrait, LocalizedDescriptionTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=2)
     */
    private $locale;

    /**
     * @var int
     *
     * @ORM\Column(name="gameType", type="integer")
     */
    private $gameType;

    /**
     * @var string
     *
     * @ORM\Column(name="visible", type="boolean", nullable=false)
     */
    private $visible;

    /**
     * @var string
     *
     * @Groups({"server"})
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    /**
     * Set id.
     *
     * @return Server
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
     * Set locale.
     *
     * @param string $locale
     *
     * @return Server
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set gameType.
     *
     * @param int $gameType
     *
     * @return Server
     */
    public function setGameType($gameType)
    {
        $this->gameType = $gameType;

        return $this;
    }

    /**
     * Get gameType.
     *
     * @return int
     */
    public function getGameType()
    {
        return $this->gameType;
    }

    /**
     * Set visible.
     *
     * @param bool $visible
     *
     * @return Server
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible.
     *
     * @return bool
     */
    public function getVisible()
    {
        return $this->visible;
    }
}
