<?php
namespace Dof\CharactersBundle;

use XN\Common\Enum;

class AreaShape extends Enum
{
	private function __construct() { }

	const POINT = 'P';
	const CIRCLE = 'C';
	const LINE = 'L';
	const CROSS = 'X';
	const SQUARE = 'G';
	const DIAGONAL_CROSS = '+';
	const HOLLOW_CROSS = 'Q';
	const CONE = 'V';
	const TRANSVERSAL_LINE = 'T';
	const CHECKERBOARD = 'D';
	const NEGATED_CIRCLE = 'I';
	const HOLLOW_DIAGONAL_CROSS = '#';
	const ARC = 'U';
	const DIAGONAL_LINE = '/'; // not sure
	const DIAGONAL_TRANSVERSAL_LINE = '-';
	const RING = 'O';
	const STAR = '*';
	const CELL = ';';
	const ALL0 = 'a';
	const ALL1 = 'A';
	const NEGATED_ROUNDED_SQUARE = 'Z';
}
