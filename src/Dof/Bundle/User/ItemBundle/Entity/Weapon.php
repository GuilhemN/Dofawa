<?php

namespace Dof\Bundle\User\ItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Weapon.
 *
 * @ORM\Entity(repositoryClass="Dof\Bundle\User\ItemBundle\Entity\WeaponRepository")
 */
class Weapon extends SkinnedItem
{
    public function isWeapon()
    {
        return true;
    }
    public function getClassId()
    {
        return 'weapon';
    }
}
