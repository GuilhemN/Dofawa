<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PetTemplate
 *
 * @ORM\Entity(repositoryClass="PetTemplateRepository")
 */
class PetTemplate extends AnimalTemplate
{
	public function __construct()
	{
		parent::__construct();
	}

	public function isPet() { return true; }
}