<?php
namespace Dof\ItemsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* Weapon
*
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\WeaponRepository")
*/
class Weapon extends Item
{
    public function isWeapon() { return true; }
}
