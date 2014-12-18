<?php
namespace Dof\ItemsBundle\Criteria;

class AndCriterion extends Criterion
{
    private $criteria;

    public function __construct(array $criteria = array()) {
        $this->criteria = $criteria;
    }

    public function setCriteria(array $criteria) {
        $this->criteria = $criteria;
        return $this;
    }

    public function getCriteria() {
        return $this->criteria;
    }
}
