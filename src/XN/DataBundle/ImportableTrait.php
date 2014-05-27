<?php

namespace XN\DataBundle;

use Doctrine\Common\Persistence\ObjectManager;

trait ImportableTrait
{
	public function importData(\Traversable $data, ObjectManager $dm)
	{
		foreach ($data as $key => $value)
			$this->importField($key, $value, $dm);
	}
	
	protected abstract function importField($key, $value, ObjectManager $dm);
}