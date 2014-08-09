<?php

namespace XN\DataBundle;

use Doctrine\Common\Persistence\ObjectManager;

trait ImportableTrait
{
	public function importData(\Traversable $data, ObjectManager $dm, $locale = 'fr')
	{
		foreach ($data as $key => $value)
			$this->importField($key, $value, $dm, $locale);
	}
	
	protected abstract function importField($key, $value, ObjectManager $dm, $locale = 'fr');
}
