<?php
namespace XN\Metadata;

use Doctrine\ORM\Mapping as ORM;
use XN\Annotations as Utils;

trait SimpleLazyFieldTrait
{
    /**
    * @var string
    *
    * @ORM\Column(name="class", type="string", length=255, nullable=true)
    */
    private $class;

    /**
    * @var integer
    *
    * @ORM\Column(name="class_id", type="integer", nullable=true)
    */
    private $classId;

    /**
    * @Utils\LazyField
    */
    private $entity;

    /**
    * Set class
    *
    * @param string $class
    * @return Notification
    */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
    * Get class
    *
    * @return string
    */
    public function getClass()
    {
        return $this->class;
    }

    /**
    * Set classId
    *
    * @param integer $classId
    * @return Notification
    */
    public function setClassId($classId)
    {
        $this->classId = $classId;

        return $this;
    }

    /**
    * Get classId
    *
    * @return integer
    */
    public function getClassId()
    {
        return $this->classId;
    }

    public function setEntity($entity){
        $this->entity = $entity;
        return $this;
    }

    public function getEntity(){
        return $this->entity;
    }
}
