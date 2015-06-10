<?php

namespace XN\Rest;

use Doctrine\Common\Persistence\ObjectManager;

trait ImportableTrait
{
    public function importData(\Traversable $data, ObjectManager $dm, $locale = 'fr')
    {
        foreach ($data as $key => $value) {
            $this->importField($key, $value, $dm, $locale);
        }
    }

    abstract protected function importField($key, $value, ObjectManager $dm, $locale = 'fr');
}
