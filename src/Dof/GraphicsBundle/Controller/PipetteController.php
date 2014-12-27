<?php

namespace Dof\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use XN\Annotations as Utils;

use Dof\ImpExpBundle\Scraper\CharacterPageScraper;

use Dof\GraphicsBundle\EntityLook;
use Dof\GraphicsBundle\EntityLookTransforms;

use Dof\GraphicsBundle\Entity\CharacterLook;

/**
 * @Utils\Secure("ROLE_STYLIST_BETA")
 */
class PipetteController extends Controller
{
    public function indexAction()
    {
        return $this->render('DofGraphicsBundle:Pipette:index.html.twig', [
            'results' => [ ]
        ]);
    }

    public function processAction(Request $req)
    {
        $results = array_map(function ($url) {
            try {
                $scraper = new CharacterPageScraper($url);
                $name = $scraper->getName();
                $serverName = $scraper->getServerName();
                $look = EntityLookTransforms::locatePC($scraper->getEntityLook());
            } catch (\Exception $e) {
                $name = null;
                $serverName = null;
                $look = null;
            }
            return (object)[
                'address' => $url,
                'name' => $name,
                'serverName' => $serverName,
                'entityLook' => $look,
                'bpcLook' => null
            ];
        }, $req->request->has('addresses') ? $req->request->get('addresses') : [ ]);
        foreach ($this->get('dof_graphics.bpcl_identifier')->identify(array_map(function (\stdClass $row) {
                return $row->entityLook;
            }, $results)) as $key => $bpcLook)
            $results[$key]->bpcLook = $bpcLook;
        return $this->render('DofGraphicsBundle:Pipette:index.html.twig', [
            'results' => $results
        ]);
    }

	public function addToGalleryAction(Request $req)
	{
		$name = $req->request->get('name');
		$look = new EntityLook($req->request->get('look'));
		$cl = new CharacterLook();
		$this->get('dof_graphics.bpcl_identifier')->identify($look, $cl);
		$cl->setName($name);
		$cl->setPubliclyVisible(false);
		$dm = $this->getDoctrine()->getManager();
		$dm->persist($cl);
		$dm->flush();
		// FIXME : dummy route/parameter names as the route doesn't exist atm
		return $this->redirect($this->get('router')->generate('dof_graphics_skins_edit', [
			'slug' => $cl->getSlug()
		]));
	}
}
