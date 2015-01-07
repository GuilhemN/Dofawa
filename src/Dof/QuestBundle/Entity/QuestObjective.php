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
    * @var integer
    *
    * @ORM\Column(name="param1", type="integer", nullable=true)
    */
    private $param1;

    /**
    * @var integer
    *
    * @ORM\Column(name="param2", type="integer", nullable=true)
    */
    private $param2;

    /**
    * @var integer
    *
    * @ORM\Column(name="param3", type="integer", nullable=true)
    */
    private $param3;

    /**
    * @var integer
    *
    * @ORM\Column(name="param4", type="integer", nullable=true)
    */
    private $param4;

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

    /**
    * Set param1
    *
    * @param integer $param1
    * @return QuestObjective
    */
    public function setParam1($param1)
    {
        $this->param1 = $param1;

        return $this;
    }

    /**
    * Get param1
    *
    * @return integer
    */
    public function getParam1()
    {
        return $this->param1;
    }

    /**
    * Set param2
    *
    * @param integer $param2
    * @return QuestObjective
    */
    public function setParam2($param2)
    {
        $this->param2 = $param2;

        return $this;
    }

    /**
    * Get param2
    *
    * @return integer
    */
    public function getParam2()
    {
        return $this->param2;
    }

    /**
    * Set param3
    *
    * @param integer $param3
    * @return QuestObjective
    */
    public function setParam3($param3)
    {
        $this->param3 = $param3;

        return $this;
    }

    /**
    * Get param3
    *
    * @return integer
    */
    public function getParam3()
    {
        return $this->param3;
    }

    /**
    * Set param4
    *
    * @param integer $param4
    * @return QuestObjective
    */
    public function setParam4($param4)
    {
        $this->param4 = $param4;

        return $this;
    }

    /**
    * Get param4
    *
    * @return integer
    */
    public function getParam4()
    {
        return $this->param4;
    }

    public function setParam($param, $id = 1) {
        call_user_func([$this, 'setParam' . $id], $param);
        return $this;
    }
    
    public function getParams() {
        return [
            1 => $this->param1,
            2 => $this->param2,
            3 => $this->param3,
            4 => $this->param4,
        ];
    }

    public function __toStringByLocale($locales){
        return $this->step->getQuest()->getName($locales);
    }

    public function __toString(){
        return $this->step->getQuest()->getName() . ' [' . $this->step->getName() . ']';
    }
}
