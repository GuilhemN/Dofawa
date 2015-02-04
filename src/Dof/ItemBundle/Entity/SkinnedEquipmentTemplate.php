<?php

namespace Dof\ItemBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\Mapping as ORM;

/**
 * SkinnedEquipmentTemplate
 *
 * @ORM\Entity(repositoryClass="Dof\ItemBundle\Entity\SkinnedEquipmentTemplateRepository")
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
    public function getClassId() { return 'skinned'; }

    public function exportData($full = true, $locale = 'fr')
    {
        return parent::exportData($full, $locale) + ($full ? [
            'skin' => $this->skin
        ] : [ ]);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        if (parent::importField($key, $value, $dm, $locale))
            return true;
        return false;
    }
}
