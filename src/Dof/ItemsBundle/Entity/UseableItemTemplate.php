<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UseableItemTemplate
 *
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\UseableItemTemplateRepository")
 */
class UseableItemTemplate extends ItemTemplate
{
	public function __construct()
	{
		parent::__construct();
	}

	public function isUseable() { return true; }
}