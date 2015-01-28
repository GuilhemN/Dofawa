<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\Mapping as ORM;

/**
 * MountTemplate
 *
 * @ORM\Entity(repositoryClass="MountTemplateRepository")
 */
class MountTemplate extends AnimalTemplate
{
    /**
     * @var array
     *
     * @ORM\Column(name="skins", type="json_array", nullable=true)
     */
    private $skins;

    /**
     * @var array
     *
     * @ORM\Column(name="colors", type="json_array", nullable=true)
     */
    private $colors;

    /**
     * @var integer
     * @ORM\Column(name="gestation_duration", type="integer", nullable=true)
     */
    private $gestationDuration;

	public function __construct()
	{
		parent::__construct();
	}

    /**
     * Set skins
     *
     * @param array $skins
     * @return MountTemplate
     */
    public function setSkins(array $skins = null)
    {
        $this->skins = $skins;

        return $this;
    }

    /**
     * Get skins
     *
     * @return array
     */
    public function getSkins()
    {
        return $this->skins;
    }

    /**
     * Set colors
     *
     * @param array $colors
     * @return MountTemplate
     */
    public function setColors(array $colors = null)
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * Get colors
     *
     * @return array
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Set gestationDuration
     *
     * @param integer $gestationDuration
     * @return MountTemplate
     */
    public function setGestationDuration($gestationDuration)
    {
        $this->gestationDuration = $gestationDuration;

        return $this;
    }

    /**
     * Get gestationDuration
     *
     * @return integer
     */
    public function getGestationDuration()
    {
        return $this->gestationDuration;
    }

	public function isMount() { return true; }
    public function getClassId() { return 'mount'; }

    public function exportData($full = true, $locale = 'fr')
    {
        return parent::exportData($full, $locale) + ($full ? [
            'skins' => $this->skins,
            'colors' => $this->colors
        ] : [ ]);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        if (parent::importField($key, $value, $dm, $locale))
            return true;
        return false;
    }
}
