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
    private $createdLocale;

    /**
     * @var string
     *
     * @ORM\Column(name="updated_locale", type="string")
     */
    private $updatedLocale;

    /**
     * Set createdLocale
     *
     * @param string $createdLocale
     * @return object
     */
    public function setCreatedLocale($createdLocale)
    {
        $this->createdLocale = $createdLocale;

        return $this;
    }

    /**
     * Get createdLocale
     *
     * @return string
     */
    public function getCreatedLocale()
    {
        return $this->createdLocale;
    }

    /**
     * Set updatedLocale
     *
     * @param string $updatedLocale
     * @return object
     */
    public function setUpdatedLocale($updatedLocale)
    {
        $this->updatedLocale = $updatedLocale;

        return $this;
    }

    /**
     * Get createdLocale
     *
     * @return string
     */
    public function getUpdatedLocale()
    {
        return $this->updatedLocale;
    }

}
