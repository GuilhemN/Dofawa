<?php

namespace Dof\Bundle\ItemBundle\Criteria;

interface ParsedCriteriaInterface
{
    public function getParsedCriteria();

    public function setTreatedCriteria($treatedCriteria);
    public function getTreatedCriteria();
}
