<?php

namespace XN\Persistence;

use Doctrine\Common\Persistence\ObjectManager;

class CollectionSynchronizationHelper
{
    private function __construct()
    {
    }

    public static function synchronize(ObjectManager $dm, array $ents, array $rows, $createEnt, $fillEnt)
    {
        $nrows = count($rows);
        $nents = count($ents);
        $nmin = min($nrows, $nents);
        $i = 0;
        for (; $i < $nmin; ++$i) {
            $ent = $ents[$i];
            $fillEnt($ent, $rows[$i]);
            $dm->persist($ent);
        }
        for (; $i < $nrows; ++$i) {
            $ent = $createEnt();
            $fillEnt($ent, $rows[$i]);
            $ents[] = $ent;
            $dm->persist($ent);
        }
        for (; $i < $nents; ++$i) {
            $dm->remove($ents[$i]);
        }
        if ($nents > $nrows) {
            return array_slice($ents, 0, $nrows);
        }

        return $ents;
    }
}
