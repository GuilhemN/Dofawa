<?php

namespace Dof\ItemsBundle;

interface PrimaryBonusInterface
{
	public function getPrimaryBonus();

	public function getPrimaryCharacteristics($metadata, array $caracts = array());
}
