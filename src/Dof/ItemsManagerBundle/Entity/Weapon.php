<?php
namespace Dof\ItemsManagerBundle\Entity;

/**
* Weapon
*
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\WeaponRepository")
*/
class Weapon extends Item
{
    public function isWeapon() { return true; }
}
