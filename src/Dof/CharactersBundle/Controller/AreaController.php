<?php
namespace Dof\CharactersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use XN\Common\UrlSafeBase64;

use Dof\CharactersBundle\Areas;

class AreaController extends Controller
{
	public function renderGlyphTrapAction($area, $color)
	{
		$area = UrlSafeBase64::decode($area);
		$polys = Areas::getPolygons($area);
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
		$minX -= 13;
		$minY -= 13;
		$maxX += 13;
		$maxY += 13;
		foreach ($polys as &$poly) {
			foreach ($poly as &$point)
				$point = ($point[0] - $minX) . ',' . ($point[1] - $minY);
			$poly = 'M ' . implode(' L ', $poly) . ' Z';
		}
		return new Response($this->renderView('DofCharactersBundle:Area:glyphTrap.svg.twig', [
			'path' => implode(' ', $polys),
			'width' => $maxX - $minX,
			'height' => $maxY - $minY,
			'color' => $color
		]), 200, [
			'Content-Type' => 'image/svg+xml'
		]);
	}
}
