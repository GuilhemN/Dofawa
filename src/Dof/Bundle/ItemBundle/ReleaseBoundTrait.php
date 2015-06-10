<?php

namespace Dof\Bundle\ItemBundle;

use Doctrine\ORM\Mapping as ORM;

trait ReleaseBoundTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="release_", type="string", length=15, nullable=true)
     */
    private $release;

    /**
     * @var bool
     *
     * @ORM\Column(name="preliminary", type="boolean")
     */
    private $preliminary;

    /**
     * @var bool
     *
     * @ORM\Column(name="deprecated", type="boolean")
     */
    private $deprecated;

    /**
     * Set release.
     *
     * @param string $release
     *
     * @return object
     */
    public function setRelease($release)
    {
        $this->release = $release;

        return $this;
    }

    /**
     * Get release.
     *
     * @return string
     */
    public function getRelease()
    {
        return $this->release;
    }

    /**
     * Set preliminary.
     *
     * @param bool $preliminary
     *
     * @return object
     */
    public function setPreliminary($preliminary)
    {
        $this->preliminary = $preliminary;

        return $this;
    }

    /**
     * Get preliminary.
     *
     * @return bool
     */
    public function getPreliminary()
    {
        return $this->preliminary;
    }

    /**
     * Get preliminary.
     *
     * @return bool
     */
    public function isPreliminary()
    {
        return $this->preliminary;
    }

    /**
     * Set deprecated.
     *
     * @param bool $deprecated
     *
     * @return object
     */
    public function setDeprecated($deprecated)
    {
        $this->deprecated = $deprecated;

        return $this;
    }

    /**
     * Get deprecated.
     *
     * @return bool
     */
    public function getDeprecated()
    {
        return $this->deprecated;
    }

    /**
     * Get deprecated.
     *
     * @return bool
     */
    public function isDeprecated()
    {
        return $this->deprecated;
    }
}
