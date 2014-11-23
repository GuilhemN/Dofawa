<?php

namespace Dof\Common;

class GameTemplateString
{
	const COMES_FROM_TEMPLATE = 0;
	const COMES_FROM_CONTEXT = 1;

	private function __construct() { }

	public static function expand($tpl, array $context)
	{
		if (!preg_match_all('/\{((?:~.)+)([^}]*)\}|#(.)/u', $tpl, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE))
			return [ [ $tpl, self::COMES_FROM_TEMPLATE ] ];
		$pos = 0;
		$result = [ ];
		foreach ($matches as $match) {
			$off = $match[0][1];
			if ($off > $pos)
				$result[] = [ substr($tpl, $pos, $off - $pos), self::COMES_FROM_TEMPLATE ];
			$pos = $off + strlen($match[0][0]);
			switch ($match[0][0][0]) {
				case '{':
					$cond = $match[1][0];
					$condlen = strlen($cond);
					for ($i = 1; $i < $condlen; $i += 2) {
						$key = $cond[$i];
						if (!isset($context[$key]) || !$context[$key])
							break 2;
					}
					$result[] = [ $match[2][0], self::COMES_FROM_TEMPLATE ];
					break;
				case '#':
					$key = $match[3][0];
					if (isset($context[$key]) && $context[$key])
						$result[] = [ $context[$key], self::COMES_FROM_CONTEXT, $key ];
					break;
			}
		}
		if (strlen($tpl) > $pos)
			$result[] = [ substr($tpl, $pos), self::COMES_FROM_TEMPLATE ];
		for ($i = count($result); $i-- > 1; ) {
			if ($result[$i][1] == self::COMES_FROM_TEMPLATE && $result[$i - 1][1] == self::COMES_FROM_TEMPLATE) {
				$result[$i - 1] = [ $result[$i - 1][0] . $result[$i][0], self::COMES_FROM_TEMPLATE ];
				array_splice($result, $i, 1);
			}
		}
		return $result;
	}
}
