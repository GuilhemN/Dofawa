<?php

namespace Dof\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\CharactersBundle\Entity\Breed;

use XN\DataBundle\IdentifiableInterface;
use XN\DataBundle\TimestampableInterface;
use XN\DataBundle\TimestampableTrait;
use XN\DataBundle\SluggableInterface;
use XN\DataBundle\SluggableTrait;
use XN\DataBundle\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

/**
 * PlayerCharacter
 *
 * @ORM\Table(name="dof_build_playercharacter")
 * @ORM\Entity(repositoryClass="Dof\BuildBundle\Entity\PlayerCharacterRepository")
 */
class PlayerCharacter implements IdentifiableInterface, TimestampableInterface, SluggableInterface, OwnableInterface
{
    use TimestampableTrait, SluggableTrait, OwnableTrait;

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
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;


    /**
     * @ORM\ManyToOne(targetEntity="Dof\CharactersBundle\Entity\Breed")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $breed;


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
     * @return PlayerCharacter
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
     * Set level
     *
     * @param integer $level
     * @return PlayerCharacter
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    public function setBreed(Breed $breed = null)
    {
        $this->breed = $breed;
        return $this;
    }

    public function getBreed()
    {
        return $this->breed;
    }
}
