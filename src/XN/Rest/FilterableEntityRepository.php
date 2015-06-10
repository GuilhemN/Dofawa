<?php

namespace XN\Rest;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class FilterableEntityRepository extends EntityRepository
{
    public function createFilteredQueryBuilder($alias, Request $req, $locale = 'fr')
    {
        return $this->addFilter($this->createQueryBuilder($alias), $req, $alias, $locale);
    }

    public function addFilter(QueryBuilder $qb, Request $req, $alias = null, $locale = 'fr')
    {
        if ($alias === null) {
            $alias = $this->getAlias($qb);
        }
        $filter = $req->query->has('filter') ? preg_split('/\s+/', strtolower(trim($req->query->get('filter')))) : null;
        if (is_array($filter) && count($filter) > 0 && strlen($filter[0]) > 0) {
            $filterable = $this->getFilterableExpr($qb, $alias, $locale);
            foreach ($filter as $i => $word) {
                $qb->andWhere($filterable.' LIKE :filterWord'.$i);
                $qb->setParameter('filterWord'.$i, '%'.addcslashes($word, '%_\\').'%');
            }
        }

        return $qb;
    }

    public function orderByText(QueryBuilder $qb, $alias = null, $locale = 'fr')
    {
        if ($alias === null) {
            $alias = $this->getAlias($qb);
        }
        $qb->addOrderBy($this->getFilterableExpr($qb, $alias, $locale), 'ASC');

        return $qb;
    }

    public function getAlias(QueryBuilder $qb)
    {
        $en = $this->getEntityName();
        $cn = $this->getClassName();
        foreach ($qb->getDQLPart('from') as $from) {
            if ($from->getFrom() == $en || $from->getFrom() == $cn) {
                return $from->getAlias();
            }
        }

        return;
    }
    public function getJoinAlias(QueryBuilder $qb, $root, $relation)
    {
        $joins = $qb->getDQLPart('join');
        if (!isset($joins[$root])) {
            return;
        }
        $relation = $root.'.'.$relation;
        foreach ($joins[$root] as $join) {
            if ($join->getJoin() == $relation) {
                return $join->getAlias();
            }
        }

        return;
    }

    abstract protected function getFilterableExpr(QueryBuilder $qb, $alias, $locale = 'fr');
}
