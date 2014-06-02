<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\DataBundle\IdentifiableInterface;
use XN\DataBundle\TimestampableInterface;
use XN\DataBundle\TimestampableTrait;
use XN\DataBundle\SluggableInterface;
use XN\DataBundle\SluggableTrait;
use XN\DataBundle\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

/**
 * Item
 *
 * @ORM\Table(name="dof_items")
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\ItemRepository")
 */
class Item implements IdentifiableInterface, TimestampableInterface, SluggableInterface, OwnableInterface
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
     * @ORM\Column(name="nom", type="string", length=150)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="idpano", type="integer")
     */
    private $idpano;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=150)
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var array
     *
     * @ORM\Column(name="element", type="simple_array")
     */
    private $element;


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
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Item
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

    /**
     * Set idpano
     *
     * @param integer $idpano
     * @return Item
     */
    public function setIdpano($idpano)
    {
        $this->idpano = $idpano;

        return $this;
    }

    /**
     * Get idpano
     *
     * @return integer 
     */
    public function getIdpano()
    {
        return $this->idpano;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Item
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Item
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
     * Set element
     *
     * @param array $element
     * @return Item
     */
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element
     *
     * @return array 
     */
    public function getElement()
    {
        return $this->element;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
