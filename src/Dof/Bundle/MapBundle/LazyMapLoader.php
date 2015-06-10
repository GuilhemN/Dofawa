<?php

namespace Dof\Bundle\MapBundle;

class LazyMapLoader
{
    private $map;

    public function __construct()
    {
    }

    public function get($id)
    {
        if (isset($this->map[$id])) {
            return $this->map[$id];
        }
        // FIXME
        //else
        //thr
    }
}
