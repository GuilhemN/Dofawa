<?php

namespace Dof\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuestObjective
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Dof\QuestBundle\Entity\QuestObjectiveRepository")
 */
class QuestObjective
{
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
     * @ORM\Column(name="mapId", type="integer")
     */
    private $mapId;

    /**
     * @var integer
     *
     * @ORM\Column(name="x", type="integer", nullable=true)
     */
    private $x;

    /**
     * @var integer
     *
     * @ORM\Column(name="y", type="integer", nullable=true)
     */
    private $y;

    /**
    * @var QuestStep
    *
    * @ORM\ManyToOne(targetEntity="QuestStep", inversedBy="objectives")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $step;

    /**
    * Set id
    *
    * @param integer $id
    * @return QuestObjective
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
     * Set mapId
     *
     * @param integer $mapId
     * @return QuestObjective
     */
    public function setMapId($mapId)
    {
        $this->mapId = $mapId;

        return $this;
    }

    /**
     * Get mapId
     *
     * @return integer
     */
    public function getMapId()
    {
        return $this->mapId;
    }

    /**
     * Set x
     *
     * @param integer $x
     * @return QuestObjective
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
     * @return QuestObjective
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
    * Set step
    *
    * @param QuestStep $step
    * @return QuestObjective
    */
    public function setStep(QuestStep $step)
    {
        $this->step = $step;

        return $this;
    }

    /**
    * Get step
    *
    * @return QuestStep
    */
    public function getStep()
    {
        return $this->step;
    }
}
