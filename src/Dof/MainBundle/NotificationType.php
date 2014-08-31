<?php

namespace Dof\MainBundle;

use XN\Common\Enum;

class NotificationType extends Enum
{
    const OTHER = 0;
	const RECEIVE_MESSAGE = 1;

	private function __construct() { }

	public static function isValid($element)
	{
		return $element >= 0 & $element <= 1;
	}
}
