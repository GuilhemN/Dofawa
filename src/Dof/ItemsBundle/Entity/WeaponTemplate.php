<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WeaponTemplate
 *
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\WeaponTemplateRepository")
 */
class WeaponTemplate extends ItemTemplate
{
	public function isWeapon() { return true; }
}