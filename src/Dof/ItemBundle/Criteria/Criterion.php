<?php
namespace Dof\ItemBundle\Criteria;

class Criterion
{
    public function getVisible() {
        return true;
    }

    public function isVisible() {
        return $this->getVisible();
    }
}
