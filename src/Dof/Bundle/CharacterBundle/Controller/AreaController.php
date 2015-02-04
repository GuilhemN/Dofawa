<?php
namespace Dof\Bundle\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use XN\Common\UrlSafeBase64;

use Dof\Bundle\CharacterBundle\Areas;
use Dof\Bundle\CharacterBundle\AreaShape;

class AreaController extends Controller
{
	// const INFINITY = (some multi-kB-long string, see near end of file);
	const INFINITY_WIDTH = 173;
	const INFINITY_HEIGHT = 78;

	public function renderGlyphTrapAction($area, $color)
	{
		$area = UrlSafeBase64::decode($area);
		$path = self::getAreaPathWithBoundingBox($area, $minX, $maxX, $minY, $maxY);
		return new Response($this->renderView('DofCharacterBundle:Area:glyphTrap.svg.twig', [
			'path' => $path,
			'width' => $maxX - $minX,
			'height' => $maxY - $minY,
			'color' => $color
		]), 200, [
			'Content-Type' => 'image/svg+xml'
		]);
	}
	public function renderNormalAction($area, $type)
	{
		$area = UrlSafeBase64::decode($area);
		$path = self::getAreaPathWithBoundingBox($area, $minX, $maxX, $minY, $maxY);
		return new Response($this->renderView('DofCharacterBundle:Area:normal.svg.twig', [
			'path' => $path,
			'width' => $maxX - $minX,
			'height' => $maxY - $minY,
			'color' => ($type == 'cast') ? '22349A' : 'DD0E21'
		]), 200, [
			'Content-Type' => 'image/svg+xml'
		]);
	}
	
	private static function getAreaPathWithBoundingBox($area, &$minX, &$maxX, &$minY, &$maxY)
	{
		$shape = Areas::getShape($area);
		if ($shape == AreaShape::ALL0 || $shape == AreaShape::ALL1) {
			$minX = 0;
			$minY = 0;
			$maxX = self::INFINITY_WIDTH;
			$maxY = self::INFINITY_HEIGHT;
			return self::INFINITY;
		} elseif ($shape == AreaShape::CELL) {
			$minX = 0;
			$maxX = 0;
			$polys = Areas::getPointPolygons();
			$polys[] = [ // the arrow pointing toward the cell
				[ -.5, -.5 ],
				[ -1, -1.5 ], [ -1.15, -1.35 ], [ -1.65, -1.85 ],
				[ -1.85, -1.65 ], [ -1.35, -1.15 ], [ -1.5, -1 ]
			];
			self::transformUVPolygonsToXYAndGetBoundingBox($polys, $minX, $maxX, $minY, $maxY);
			self::stringifyXYPolygons($polys, $minX, $minY);
			return implode(' ', $polys);
		} else {
			$polys = Areas::getPolygons($area);
			self::addTargetedCellMarker($polys);
			self::transformUVPolygonsToXYAndGetBoundingBox($polys, $minX, $maxX, $minY, $maxY);
			self::stringifyXYPolygons($polys, $minX, $minY);
			return implode(' ', $polys);
		}
	}
	private static function addTargetedCellMarker(array &$polys)
	{
		$polys[] = [ [ .4, .4 ], [ .6, .6 ] ];
		$polys[] = [ [ .6, .4 ], [ .4, .6 ] ];
	}
	private static function transformUVPolygonsToXYAndGetBoundingBox(array &$polys, &$minX, &$maxX, &$minY, &$maxY)
	{
		$minX = 0;
		$maxX = 0;
		$minY = 0;
		$maxY = 0;
		foreach ($polys as &$poly) {
			foreach ($poly as &$point) {
				$point = [
					($point[0] - $point[1]) * 26,
					($point[0] + $point[1]) * 13
				];
				$minX = min($minX, $point[0]);
				$maxX = max($maxX, $point[0]);
				$minY = min($minY, $point[1]);
				$maxY = max($maxY, $point[1]);
			}
		}
		// Limit to u and v between -15 and 16 (incl.)
		$minX = max($minX, -780) - 3;
		$minY = max($minY, -390) - 3;
		$maxX = min($maxX, 806) + 3;
		$maxY = min($maxY, 403) + 3;
	}
	private static function stringifyXYPolygons(array &$polys, $minX, $minY)
	{
		foreach ($polys as &$poly) {
			foreach ($poly as &$point)
				$point = ($point[0] - $minX) . ',' . ($point[1] - $minY);
			$poly = 'M ' . implode(' L ', $poly) . ' Z';
		}
	}

	// Warning : multi-kB-long constant below
	const INFINITY = 'M87.16623,31.415329C93.74946,25.082085 100.08279,19.707091 106.16623,15.290329C112.24945,10.790433 117.37444,7.790436 121.54123,6.290329C125.70777,4.790439 130.24943,4.04044 135.16623,4.040329C145.49941,4.04044 153.83274,7.498769 160.16623,14.415329C166.58273,21.248756 169.79106,29.540414 169.79123,39.290329C169.79106,45.957064 168.37439,52.123725 165.54123,57.790329C162.70773,63.457047 158.66607,67.748709 153.41623,70.665329C148.24941,73.582037 142.29108,75.040369 135.54123,75.040329C126.7911,75.040369 119.12444,73.16537 112.54123,69.415329C106.04112,65.665378 97.58279,58.582052 87.16623,48.165329C76.332815,58.915385 67.707824,66.082044 61.291229,69.665329C54.874504,73.248704 47.416178,75.040369 38.916229,75.040329C28.082864,75.040369 19.624539,71.665372 13.541229,64.915329C7.541218,58.165385 4.541221,49.623727 4.541229,39.290329C4.541221,29.623747 7.707884,21.332089 14.041229,14.415329C20.457871,7.498769 28.832863,4.04044 39.166229,4.040329C44.166181,4.04044 48.74951,4.790439 52.916229,6.290329C57.082835,7.790436 62.166163,10.790433 68.166229,15.290329C74.249484,19.707091 80.582811,25.082085 87.16623,31.415329 M95.29123,39.165329C104.12445,47.915396 111.37445,53.873723 117.04123,57.040329C122.7911,60.123717 128.49943,61.665382 134.16623,61.665329C141.24942,61.665382 146.79108,59.582051 150.79123,55.415329C154.79107,51.165392 156.79107,46.040398 156.79123,40.040329C156.79107,33.457077 154.79107,28.040416 150.79123,23.790329C146.87441,19.457091 141.66608,17.290426 135.16623,17.290329C131.49943,17.290426 127.95776,17.957092 124.54123,19.290329C121.12444,20.540423 117.04111,22.790421 112.29123,26.040329C107.54112,29.207081 101.87446,33.582077 95.29123,39.165329 M79.041229,39.165329C73.041152,34.082076 67.666157,29.915414 62.916229,26.665329C58.166167,23.332087 53.999504,20.957089 50.416229,19.540329C46.832845,18.123759 42.916182,17.415426 38.666229,17.415329C32.582859,17.415426 27.541198,19.540424 23.541229,23.790329C19.541206,28.040416 17.541208,33.457077 17.541229,40.040329C17.541208,44.623732 18.582873,48.498728 20.666229,51.665329C22.749536,54.832055 25.2912,57.290386 28.291229,59.040329C31.374527,60.790383 35.207857,61.665382 39.791229,61.665329C45.791179,61.665382 51.624507,60.08205 57.291229,56.915329C62.957829,53.748723 70.207822,47.832062 79.041229,39.165329';
}
