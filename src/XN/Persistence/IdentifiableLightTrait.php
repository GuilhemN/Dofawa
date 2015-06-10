<?php

namespace XN\Persistence;

trait IdentifiableLightTrait
{
    /**
     * Get id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param string $id
     *
     * @return IdentifiableInterface
     */
    public function setId($id)
    {
        if (is_numeric($id)) {
            $this->id = $id;
        } else {
            $this->id = null;
        }

        return $this;
    }

    protected function exportIdentifiableData($full = true)
    {
        return ['id' => $this->id];
    }
}
