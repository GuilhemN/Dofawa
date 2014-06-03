<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MountTemplate
 *
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\MountTemplateRepository")
 */
class MountTemplate extends EquipmentTemplate
{
	public function __construct()
	{
		parent::__construct();
	}

	public function isMount() { return true; }
}