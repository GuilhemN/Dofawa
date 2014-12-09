<?php
namespace Dof\ItemsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* SkinnedItem
*
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\SkinnedItemRepository")
*/
class SkinnedItem extends Item
{

    public function isSkinned() { return true; }
    public function getClassId() { return 'skitem'; }
}
