<?php
namespace Dof\ItemsBundle\Criteria;

class Criterion
{
    public function getVisible() {
        return true;
    }

    public function isVisible() {
        return $this->getVisible();
    }
}
