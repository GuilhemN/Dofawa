<?php

namespace Dof\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Dof\ImpExpBundle\Scraper\CharacterPageScraper;

use Dof\GraphicsBundle\EntityLook;
use Dof\GraphicsBundle\EntityLookTransforms;

class PipetteController extends Controller
{
    public function indexAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_STYLIST_BETA'))
            throw new AccessDeniedException();
        return $this->render('DofGraphicsBundle:Pipette:index.html.twig', [
            'results' => [ ]
        ]);
    }

    public function processAction(Request $req)
    {
        if (!$this->get('security.context')->isGranted('ROLE_STYLIST_BETA'))
            throw new AccessDeniedException();
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
}
