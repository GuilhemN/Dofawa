<?php

namespace Dof\ItemsBundle\Criteria;

interface ParsedCriteriaInterface
{
    public function getParsedCriteria();
    
    public function setTreatedCriteria(array $treatedCriteria);
    public function getTreatedCriteria();
}
