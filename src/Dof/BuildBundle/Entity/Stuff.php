<?php

namespace Dof\BuildBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use XN\DataBundle\IdentifiableInterface;
use XN\DataBundle\TimestampableInterface;
use XN\DataBundle\TimestampableTrait;
use XN\DataBundle\SluggableInterface;
use XN\DataBundle\SluggableTrait;

use Doctrine\ORM\Mapping as ORM;

use Dof\BuildBundle\Entity\PlayerCharacter;
use Dof\BuildBundle\Entity\Item;
use Dof\GraphicsBundle\Entity\BuildLook;

/**
 * Stuff
 *
 * @ORM\Table(name="dof_build_stuff")
 * @ORM\Entity(repositoryClass="Dof\BuildBundle\Entity\StuffRepository")
 */
class Stuff implements IdentifiableInterface, TimestampableInterface, SluggableInterface
{
    use TimestampableTrait, SluggableTrait;

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\BuildBundle\Entity\PlayerCharacter", inversedBy="stuffs")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $character;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\Item", mappedBy="stuff")
     */
    private $items;

    /**
     * @ORM\OneToOne(targetEntity="Dof\GraphicsBundle\Entity\BuildLook", inversedBy="stuff")
     */
    private $look;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }


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
     * @return Stuff
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
     * Set character
     *
     * @param PlayerCharacter $character
     * @return Stuff
     */
    public function setCharacter(PlayerCharacter $character)
    {
        $this->character = $character;

        return $this;
    }

    /**
     * Get character
     *
     * @return PlayerCharacter
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * Add items
     *
     * @param Item $items
     * @return Stuff
     */
    public function addItem(Item $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param Item $items
     * @return Stuff
     */
    public function removeItem(Item $items)
    {
        $this->items->removeElement($items);

        return $this;
    }

    /**
     * Get items
     *
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set look
     *
     * @param BuildLook $look
     * @return Stuff
     */
    public function setLook(BuildLook $look)
    {
        $this->look = $look;

        return $this;
    }

    /**
     * Get look
     *
     * @return BuildLook
     */
    public function getLook()
    {
        return $this->look;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
