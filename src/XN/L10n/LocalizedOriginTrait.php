<?php

namespace XN\L10n;

use Doctrine\ORM\Mapping as ORM;

trait LocalizedOriginTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="created_locale", type="string")
     */
    private $createdOnLocale;

    /**
     * @var string
     *
     * @ORM\Column(name="updated_locale", type="string")
     */
    private $updatedOnLocale;

    /**
     * Set createdOnLocale
     *
     * @param string $createdOnLocale
     * @return object
     */
    public function setCreatedOnLocale($createdOnLocale)
    {
        $this->createdOnLocale = $createdOnLocale;

        return $this;
    }

    /**
     * Get createdOnLocale
     *
     * @return string
     */
    public function getCreatedOnLocale()
    {
        return $this->createdOnLocale;
    }

    /**
     * Set updatedOnLocale
     *
     * @param string $updatedOnLocale
     * @return object
     */
    public function setUpdatedOnLocale($updatedOnLocale)
    {
        $this->updatedOnLocale = $updatedOnLocale;

        return $this;
    }

    /**
     * Get updatedOnLocale
     *
     * @return string
     */
    public function getUpdatedOnLocale()
    {
        return $this->updatedOnLocale;
    }

}
