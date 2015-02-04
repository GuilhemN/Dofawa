<?php

namespace Dof\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\ItemBundle\ReleaseBoundTrait;

/**
 * QuestObjectiveType
 *
 * @ORM\Table(name="dof_quest_objective_types")
 * @ORM\Entity(repositoryClass="Dof\QuestBundle\Entity\QuestObjectiveTemplateRepository")
 */
class QuestObjectiveTemplate implements IdentifiableInterface, LocalizedNameInterface
{
    use LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;


    /**
    * Set id
    *
    * @return QuestObjectiveType
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
}
