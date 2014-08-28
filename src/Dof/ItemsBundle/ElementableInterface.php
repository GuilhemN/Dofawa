<?php

namespace Dof\ItemsBundle;

interface ElementableInterface
{
	public function getElements();

	public function getCharacteristicsForElements($metadata, array $caracts = array());
}
