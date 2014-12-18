<?php

namespace Dof\ItemsBundle\Criteria;

interface ParsedCriteriaInterface
{
    public function getParsedCriteria();

    public function setTreatedCriteria($treatedCriteria);
    public function getTreatedCriteria();
}
