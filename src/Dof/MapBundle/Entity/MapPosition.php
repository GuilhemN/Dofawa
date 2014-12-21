<?php

namespace Dof\MapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;

# L'interface n'est pas utilisé afin que le __toString() soit utilisé et non le nom de l'entité
# use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * MapPosition
 *
 * @ORM\Table(name="dof_map_positions")
 * @ORM\Entity(repositoryClass="Dof\MapBundle\Entity\MapPositionRepository")
 */
class MapPosition implements IdentifiableInterface, TimestampableInterface
{
    use TimestampableTrait, LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="x", type="integer")
     */
    private $x;

    /**
     * @var integer
     *
     * @ORM\Column(name="y", type="integer")
     */
    private $y;

    /**
     * @var boolean
     *
     * @ORM\Column(name="outdoor", type="boolean")
     */
    private $outdoor;

    /**
     * @var integer
     *
     * @ORM\Column(name="capabilities", type="integer")
     */
    private $capabilities;

    /**
    * @var integer
    *
    * @ORM\Column(name="worldMap", type="integer")
    */
    private $worldMap;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hasPriorityOnWorldMap", type="boolean")
     */
    private $hasPriorityOnWorldMap;

    /**
    * @var Monster
    *
    * @ORM\ManyToOne(targetEntity="SubArea", inversedBy="maps")
    * @ORM\JoinColumn(nullable=true)
    */
    private $subArea;

    /**
    * Set id
    *
    * @param integer $id
    * @return MapPosition
    */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set x
     *
     * @param integer $x
     * @return MapPosition
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x
     *
     * @return integer
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set y
     *
     * @param integer $y
     * @return MapPosition
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y
     *
     * @return integer
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Set outdoor
     *
     * @param boolean $outdoor
     * @return MapPosition
     */
    public function setOutdoor($outdoor)
    {
        $this->outdoor = $outdoor;

        return $this;
    }

    /**
     * Get outdoor
     *
     * @return boolean
     */
    public function getOutdoor()
    {
        return $this->outdoor;
    }

    /**
     * Set capabilities
     *
     * @param integer $capabilities
     * @return MapPosition
     */
    public function setCapabilities($capabilities)
    {
        $this->capabilities = $capabilities;

        return $this;
    }

    /**
     * Get capabilities
     *
     * @return integer
     */
    public function getCapabilities()
    {
        return $this->capabilities;
    }

    /**
    * Set worldMap
    *
    * @param integer $worldMap
    * @return MapPosition
    */
    public function setWorldMap($worldMap)
    {
        $this->worldMap = $worldMap;

        return $this;
    }

    /**
    * Get worldMap
    *
    * @return integer
    */
    public function getWorldMap()
    {
        return $this->worldMap;
    }

    /**
     * Set hasPriorityOnWorldMap
     *
     * @param boolean $hasPriorityOnWorldMap
     * @return MapPosition
     */
    public function setHasPriorityOnWorldMap($hasPriorityOnWorldMap)
    {
        $this->hasPriorityOnWorldMap = $hasPriorityOnWorldMap;

        return $this;
    }

    /**
     * Get hasPriorityOnWorldMap
     *
     * @return boolean
     */
    public function getHasPriorityOnWorldMap()
    {
        return $this->hasPriorityOnWorldMap;
    }

    /**
    * Set subArea
    *
    * @param SubArea $subArea
    * @return MapPosition
    */
    public function setSubArea(SubArea $subArea = null)
    {
        $this->subArea = $subArea;

        return $this;
    }

    /**
    * Get subArea
    *
    * @return SubArea
    */
    public function getSubArea()
    {
        return $this->subArea;
    }

    public function __toString() {
        return '[' . $this->x . ', ' . $this->y . ']';
    }
}
