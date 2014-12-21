<?php
namespace XN\BBCode;

use XN\Common\Enum;

class ParserError extends Enum
{
	const NONE = 0;
	const EXTRA_DATA = -1;
	const EXPECTED_TEXT = -2;
	const EXPECTED_OTAG_START = -3;
	const UNKNOWN_TAG = -4;
	const EXPECTED_OTAG_END = -5;
	const EXPECTED_CTAG_START = -6;
	const EXPECTED_CTAG_END = -7;
	const EXPECTED_IDENTIFIER = -8;
	const EXPECTED_BOOLEAN = -9;
	const EXPECTED_NUMBER = -10;
	const EXPECTED_STRING = -11;
	const INVALID_ESCAPE = -12;
	const REJECTED_NODE = -13;

	public static function isValid($error)
	{
		return $error >= -13;
	}
}
