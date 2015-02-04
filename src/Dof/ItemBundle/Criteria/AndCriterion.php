<?php
namespace Dof\ItemBundle\Criteria;

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

    public function countVisibleCriteria() {
        return count(array_filter($this->criteria, function($v) {
            return $v->getVisible();
        }));
    }

    public function isStructureVisible() {
        return $this->countVisibleCriteria() > 1;
    }
}
