<?php

namespace Dof\ItemsBundle\Entity;

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

	public function isMount() { return true; }
}