<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\ItemsBundle\CharacteristicsRangeTrait;

/**
 * EquipmentTemplate
 *
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\EquipmentTemplateRepository")
 */
class EquipmentTemplate extends ItemTemplate
{
	use CharacteristicsRangeTrait;

	public function __construct()
	{
		parent::__construct();
	}

	public function isEquipment() { return true; }
}