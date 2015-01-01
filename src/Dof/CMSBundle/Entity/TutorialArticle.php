<?php

namespace Dof\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TutorialArticle
 *
 * @ORM\Entity(repositoryClass="Dof\CMSBundle\Entity\TutorialArticleRepository")
 */
class TutorialArticle extends Article
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

    public function getEntity() {
        return $this->entity;
    }

    public function setEntity($entity) {
        $this->entity = $entity;
        return $this;
    }

    public function isTutorial() { return true; }
    public function getClass() { return 'tutorial'; }
}
