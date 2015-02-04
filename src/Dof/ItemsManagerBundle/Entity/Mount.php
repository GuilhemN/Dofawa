<?php
namespace Dof\ItemsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dof\ItemBundle\Entity\MountTemplate;

/**
* Mount
*
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\MountRepository")
*/
class Mount extends Animal
{

    /**
    * @var Mount
    *
    * @ORM\ManyToOne(targetEntity="Dof\ItemBundle\Entity\MountTemplate")
    * @ORM\JoinColumn(onDelete="SET NULL")
    */
    private $mimibioteTemplate;


    /**
    * Set mimibioteTemplate
    *
    * @param Mount $mimibioteTemplate
    * @return SkinnedItem
    */
    public function setMimibioteTemplate(MountTemplate $mimibioteTemplate)
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

    public function isMount() { return true; }
    public function getClassId() { return 'mount'; }
}
