<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PetTemplate
 *
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\PetTemplateRepository")
 */
class PetTemplate extends EquipmentTemplate
{
	public function __construct()
	{
		parent::__construct();
	}

	public function isPet() { return true; }
}