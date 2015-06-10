<?php

namespace Dof\Bundle\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * UserRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    /**
     * Get the paginated list of the users.
     *
     * @param int    $page
     * @param int    $maxperpage
     * @param string $sortby
     *
     * @return Paginator
     */
    public function getList($page = 1, $maxperpage = 10)
    {
        $q = $this->_em->createQueryBuilder()
        ->select('user')
        ->from('DofUserBundle:user', 'user')
        ;

        $q->setFirstResult(($page - 1) * $maxperpage)
        ->setMaxResults($maxperpage);

        return new Paginator($q);
    }

    /**
     * Count all users.
     *
     * @return int
     */
    public function countTotal()
    {
        return $this->createQueryBuilder('a')
     ->select('COUNT(a)')
     ->getQuery()
     ->getSingleScalarResult();
    }

    /**
     * Count all users.
     *
     * @return int
     */
    public function deleteById($id)
    {
        $query = $this->_em->createQuery('DELETE DofUserBundle:User u WHERE u.id = :id');
        $query->setParameter('id', $id);

        return $query->execute();
    }
}
