<?php
namespace Dof\MapBundle;

class LazyMapLoader
{
    private $map;

    public function __construct(){}

    public function get($id) {
        if(isset($this->map[$id]))
            return $this->map[$id];
        else
        thr
    }
}
