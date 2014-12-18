<?php
namespace Dof\ItemsBundle\Criteria;

use XN\Grammar\StringReader;

trait ParsedCriteriaTrait
{
    private $treatedCriteria = null;

    public function getParsedCriteria() {
        return CriteriaParser::criteria(new StringReader($this->getCriteria()));
    }

    public function setTreatedCriteria($treatedCriteria){
        $this->treatedCriteria = $treatedCriteria;
        return $this;
    }

    public function getTreatedCriteria(){
        return $this->treatedCriteria;
    }
}
