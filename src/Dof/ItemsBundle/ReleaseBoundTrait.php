<?php

namespace Dof\ItemsBundle;

use Doctrine\ORM\Mapping as ORM;

trait ReleaseBoundTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="release", type="string", length=15, nullable=true)
     */
    private $release;

    /**
     * @var boolean
     *
     * @ORM\Column(name="preliminary", type="boolean")
     */
    private $preliminary;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deprecated", type="boolean")
     */
    private $deprecated;

    /**
     * Set release
     *
     * @param string $release
     * @return object
     */
    public function setRelease($release)
    {
        $this->release = $release;

        return $this;
    }

    /**
     * Get release
     *
     * @return string
     */
    public function getRelease()
    {
        return $this->release;
    }

    /**
     * Set preliminary
     *
     * @param boolean $preliminary
     * @return object
     */
    public function setPreliminary($preliminary)
    {
        $this->preliminary = $preliminary;

        return $this;
    }

    /**
     * Get preliminary
     *
     * @return boolean
     */
    public function getPreliminary()
    {
        return $this->preliminary;
    }

    /**
     * Get preliminary
     *
     * @return boolean
     */
    public function isPreliminary()
    {
        return $this->preliminary;
    }

    /**
     * Set deprecated
     *
     * @param boolean $deprecated
     * @return object
     */
    public function setDeprecated($deprecated)
    {
        $this->deprecated = $deprecated;

        return $this;
    }

    /**
     * Get deprecated
     *
     * @return boolean
     */
    public function getDeprecated()
    {
        return $this->deprecated;
    }

    /**
     * Get deprecated
     *
     * @return boolean
     */
    public function isDeprecated()
    {
        return $this->deprecated;
    }
}
