<?php

namespace Dof\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
            $look = null;
            if (preg_match('~^http://www\.dofus\.com/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+/[0-9a-z-]+$~', $url)) {
                $doc = file_get_contents($url);
                if (preg_match('~' . EntityLook::AK_RENDERER_PATTERN . '~', $doc, $matches)) {
                    try {
                        $look = EntityLookTransforms::locatePC(new EntityLook(hex2bin($matches[1])));
                    } catch (\Exception $e) { }
                }
            }
            return (object)[
                'address' => $url,
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
