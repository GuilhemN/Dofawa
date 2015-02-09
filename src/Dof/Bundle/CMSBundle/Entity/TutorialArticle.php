<?php

namespace Dof\Bundle\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Annotations as Utils;

/**
 * TutorialArticle
 *
 * @ORM\Entity(repositoryClass="Dof\Bundle\CMSBundle\Entity\TutorialArticleRepository")
 */
class TutorialArticle extends Article
{
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
    * @Utils\LazyField(classProperty="entityClass")
    */
    private $entity;


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

    public function isTutorialArticle() { return true; }
    public function getClass() { return 'tutorial'; }
}