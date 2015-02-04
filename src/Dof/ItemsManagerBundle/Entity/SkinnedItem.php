<?php
namespace Dof\ItemsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\ItemBundle\Entity\SkinnedEquipmentTemplate;

/**
* SkinnedItem
*
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\SkinnedItemRepository")
*/
class SkinnedItem extends Item
{

    /**
    * @var SkinnedEquipmentTemplate
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemBundle\Entity\SkinnedEquipmentTemplate")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    protected $mimibioteTemplate;


    /**
    * Set mimibioteTemplate
    *
    * @param SkinnedEquipmentTemplate $mimibioteTemplate
    * @return SkinnedItem
    */
    public function setMimibioteTemplate(SkinnedEquipmentTemplate $mimibioteTemplate)
    {
        $this->mimibioteTemplate = $mimibioteTemplate;

        return $this;
    }

    /**
    * Get mimibioteTemplate
    *
    * @return mimibioteTemplate
    */
    public function getMimibioteTemplate()
    {
        return ($this->mimibioteTemplate !== null) ? $this->mimibioteTemplate : $this->itemTemplate;
    }

    public function isSkinned() { return true; }
    public function getClassId() { return 'skitem'; }
}
