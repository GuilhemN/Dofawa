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
     * Set release
     *
     * @param string $release
     * @return ItemTemplate
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
}