<?php

namespace Dof\Bundle\User\ItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dof\Bundle\ItemBundle\Entity\PetTemplate;

/**
 * Pet.
 *
 * @ORM\Entity(repositoryClass="Dof\Bundle\User\ItemBundle\Entity\PetRepository")
 */
class Pet extends Animal
{
    /**
     * @var PetTemplate
     *
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\ItemBundle\Entity\PetTemplate")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $mimibioteTemplate;

    /**
     * Set mimibioteTemplate.
     *
     * @param Mount $mimibioteTemplate
     *
     * @return SkinnedItem
     */
    public function setMimibioteTemplate(PetTemplate $mimibioteTemplate)
    {
        $this->mimibioteTemplate = $mimibioteTemplate;

        return $this;
    }

    /**
     * Get mimibioteTemplate.
     *
     * @return mimibioteTemplate
     */
    public function getMimibioteTemplate()
    {
        return ($this->mimibioteTemplate !== null) ? $this->mimibioteTemplate : $this->itemTemplate;
    }

    public function isPet()
    {
        return true;
    }
    public function getClassId()
    {
        return 'pet';
    }
}
