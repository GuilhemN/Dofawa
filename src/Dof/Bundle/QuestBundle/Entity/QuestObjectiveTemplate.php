<?php

namespace Dof\Bundle\QuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * QuestObjectiveTemplate.
 *
 * @ORM\Table(name="dof_quest_objective_templates")
 * @ORM\Entity(repositoryClass="Dof\Bundle\QuestBundle\Entity\QuestObjectiveTemplateRepository")
 */
class QuestObjectiveTemplate implements IdentifiableInterface, LocalizedNameInterface
{
    use LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * Set id.
     *
     * @return QuestObjectiveTemplate
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
