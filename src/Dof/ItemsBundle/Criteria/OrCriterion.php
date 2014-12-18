<?php
namespace Dof\ItemsBundle\Criteria;

class OrCriterion extends Criterion
{
    private $criterions;

    public function __construct(array $criterions = array()) {
        $this->criterions = $criterions;
    }

    public function setCriterions(array $criterions) {
        $this->criterions = $criterions;
        return $this;
    }

    public function getCriterions() {
        return $this->criterions;
    }
}
