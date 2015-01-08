<?php
namespace XN\UtilityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Annotations as Utils;

use XN\Persistence\IdentifiableInterface;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;

/**
* NameIndex
*
* @ORM\Table(name="xn_name_index")
* @ORM\Entity(repositoryClass="XN\UtilityBundle\Entity\NameIndexRepository")
*/
class NameIndex implements IdentifiableInterface, LocalizedNameInterface
{
    /**
    * @var integer
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    use LocalizedNameTrait;

    /**
    * @var string
    *
    * @ORM\Column(name="entity_class", type="string", length=255, nullable=true)
    */
    private $entityClass;

    /**
    * @var integer
    *
    * @ORM\Column(name="class_id", type="integer", nullable=true)
    */
    private $classId;

    /**
    * @Utils\LazyField(classMethod="getEntityClass")
    */
    private $entity;


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
    * Set entityClass
    *
    * @param string $entityClass
    * @return TutorialArticle
    */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
    * Get entityClass
    *
    * @return string
    */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
    * Set classId
    *
    * @param integer $classId
    * @return TutorialArticle
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

    public function getEntity() {
        return $this->entity;
    }

    public function setEntity($entity) {
        $this->entity = $entity;
        return $this;
    }
}
