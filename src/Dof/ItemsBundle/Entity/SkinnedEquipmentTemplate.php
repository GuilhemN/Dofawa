<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SkinnedEquipmentTemplate
 *
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\SkinnedEquipmentTemplateRepository")
 */
class SkinnedEquipmentTemplate extends EquipmentTemplate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="skin", type="integer", nullable=true, unique=false)
     */
    private $skin;

	public function __construct()
	{
		parent::__construct();
	}

    /**
     * Set skin
     *
     * @param integer $skin
     * @return SkinnedEquipmentTemplate
     */
    public function setSkin($skin)
    {
        $this->skin = $skin;

        return $this;
    }

    /**
     * Get skin
     *
     * @return integer
     */
    public function getSkin()
    {
        return $this->skin;
    }

	public function isSkinned() { return true; }
}
