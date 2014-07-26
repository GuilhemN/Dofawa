<?php
namespace XN\DataBundle;

use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class FilterableEntityRepository extends EntityRepository
{
	public function createFilteredQueryBuilder($alias, Request $req)
	{
		return $this->addFilter($this->createQueryBuilder($alias), $req, $alias);
	}

	public function addFilter(QueryBuilder $qb, Request $req, $alias = null)
	{
		if ($alias === null)
			$alias = $this->getAlias($qb);
		$filter = $req->query->has('filter') ? preg_split('/\s+/', strtolower(trim($req->query->get('filter')))) : null;
		if (is_array($filter) && count($filter) > 0 && strlen($filter[0]) > 0) {
			$filterable = $this->getFilterableExpr($qb, $alias);
			foreach ($filter as $i => $word) {
				$qb->andWhere($filterable . ' LIKE :filterWord' . $i);
				$qb->setParameter('filterWord' . $i, '%' . addcslashes($word, '%_\\') . '%');
			}
		}
		return $qb;
	}

	public function getAlias(QueryBuilder $qb)
	{
		$en = $this->getEntityName();
		$cn = $this->getClassName();
		foreach ($qb->getDQLPart('from') as $from)
			if ($from->getFrom() == $en || $from->getFrom() == $cn)
				return $from->getAlias();
		return null;
	}
	public function getJoinAlias(QueryBuilder $qb, $root, $relation)
	{
		$joins = $qb->getDQLPart('join');
		if (!isset($joins[$root]))
			return null;
		$relation = $root . '.' . $relation;
		foreach ($joins[$root] as $join)
			if ($join->getJoin() == $relation)
				return $join->getAlias();
		return null;
	}

	protected abstract function getFilterableExpr(QueryBuilder $qb, $alias);

	public abstract function orderByText(QueryBuilder $qb, $alias = null);
}
