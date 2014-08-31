<?php

namespace Dof\ItemsBundle;

interface PrimaryBonusInterface
{
	public function getPrimaryBonus();

	public function getCharacteristicsForPrimaryBonus(array $primaryFields, array $caracts = array());
}
