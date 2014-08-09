<?php

namespace XN\DataBundle;

use Doctrine\Common\Persistence\ObjectManager;

interface ExportableInterface
{
	public function exportData($full = true, $locale = 'fr');
	public function importData(\Traversable $data, ObjectManager $dm, $locale = 'fr');
}
