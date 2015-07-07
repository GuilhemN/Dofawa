<?php

namespace XN\Metadata;

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
