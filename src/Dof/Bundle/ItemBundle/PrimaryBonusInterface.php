<?php

namespace Dof\Bundle\ItemBundle;

interface PrimaryBonusInterface
{
	public function getPrimaryBonus();

	public function getCharacteristicsForPrimaryBonus(array $primaryFields, array $caracts = array());
}
