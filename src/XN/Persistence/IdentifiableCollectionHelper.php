<?php

namespace XN\Persistence;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use XN\Common\This;

class IdentifiableCollectionHelper
{
    private function __construct()
    {
    }

    public static function getIds(Collection $coll)
    {
        return array_map(['XN\Common\This', 'getId'], $coll->toArray());
    }

    public static function setIds(Collection $coll, array $ids, EntityRepository $repo, $inverseGetter = null, IdentifiableInterface $that = null)
    {
        if (count($ids) == 0) {
            return self::clear($coll, $inverseGetter, $that);
        }

        $inverse = $inverseGetter !== null && $that !== null;

        $elems = $coll->toArray();
        $elems = array_combine(array_map([This::class, 'getId'], $elems), $elems);
        $ids = array_flip($ids);
        ksort($elems);
        ksort($ids);

        $removals = array_diff_key($elems, $ids);
        $inserts = array_diff_key($ids, $elems);

        foreach ($repo->findBy(['id' => array_keys($inserts)]) as $ent) {
            $inserts[$ent->getId()] = $ent;
        }
        foreach ($inserts as $id => $ent) {
            if (!is_object($ent)) {
                throw new \Exception('Entity '.$id.' not found');
            }
        }

        foreach ($removals as $ent) {
            $coll->removeElement($ent);
            if ($inverse) {
                call_user_func([$ent, $inverseGetter])->removeElement($that);
            }
        }

        foreach ($inserts as $ent) {
            $coll[] = $ent;
            if ($inverse) {
                call_user_func([$ent, $inverseGetter])->add($that);
            }
        }

        return [$inserts, $removals];
    }

    public static function clear(Collection $coll, $inverseGetter = null, IdentifiableInterface $that = null)
    {
        $inverse = $inverseGetter !== null && $that !== null;

        $elems = $coll->toArray();
        $elems = array_combine(array_map([This::class, 'getId'], $elems), $elems);

        if ($inverse) {
            foreach ($elems as $ent) {
                $coll->removeElement($ent);
                call_user_func([$ent, $inverseGetter])->removeElement($that);
            }
        } else {
            $coll->clear();
        }

        return [[], $elems];
    }
}
