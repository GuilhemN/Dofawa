<?php

namespace XN\Metadata;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait SluggableTrait
{
    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return SluggableTrait
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }
}
