<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Dof\CharactersBundle\Gender;

/**
 * FaceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FaceRepository extends EntityRepository
{
    public function findBySkinIds(array $skinIds)
    {
        $skins = array();
        foreach ($this->createQueryBuilder('f')
            ->where('f.id IN (:skinIds)')
            ->setParameter('skinIds', $skinIds)
            ->getQuery()
            ->getResult() as $skin)
            $skins[$skin->getId()] = $skin;
        ksort($skins);
        return $skins;
    }

    public function findForCharacterLook(Breed $breed, $gender, $label) {
        if(!Gender::isValid($gender))
          $gender = Gender::MALE;

        return $this
                  ->createQueryBuilder('fa')
                  ->select(array('fa.*'))
                  ->where('fa.breed = :breed AND fa.gender = :gender and fa.label = :label')
                  ->getQuery()
                  ->setParameter('breed', $breed)
                  ->setParameter('gender', $gender)
                  ->setParameter('label', $label)
                  ->getResult()
              ;
    }
}
