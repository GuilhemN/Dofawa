<?php

namespace Dof\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

use Dof\MapBundle\Entity\MapPosition;

/**
 * QuestObjective
 *
 * @ORM\Table(name="dof_quest_step_objectives")
 * @ORM\Entity(repositoryClass="Dof\QuestBundle\Entity\QuestObjectiveRepository")
 */
class QuestObjective implements IdentifiableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
    * @var QuestObjectiveTemplate
    *
    * @ORM\ManyToOne(targetEntity="QuestObjectiveTemplate")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $objectiveTemplate;

    /**
    * @var MapPosition
    *
    * @ORM\ManyToOne(targetEntity="Dof\MapBundle\Entity\MapPosition")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $map;

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
    * Set objectiveTemplate
    *
    * @param QuestObjectiveTemplate $objectiveTemplate
    * @return QuestObjective
    */
    public function setObjectiveTemplate(QuestObjectiveTemplate $objectiveTemplate)
    {
        $this->objectiveTemplate = $objectiveTemplate;

        return $this;
    }

    /**
    * Get objectiveTemplate
    *
    * @return QuestObjectiveTemplate
    */
    public function getObjectiveTemplate()
    {
        return $this->objectiveTemplate;
    }

    /**
    * Set map
    *
    * @param MapPosition $map
    * @return QuestObjective
    */
    public function setMap(MapPosition $map = null)
    {
        $this->map = $map;

        return $this;
    }

    /**
    * Get map
    *
    * @return MapPosition
    */
    public function getMap()
    {
        return $this->map;
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

    public function __toStringByLocale($locales){
        return $this->step->getQuest()->getName($locales);
    }

    public function __toString(){
        return $this->step->getQuest()->getName() . ' [' . $this->step->getName() . ']';
    }
}
