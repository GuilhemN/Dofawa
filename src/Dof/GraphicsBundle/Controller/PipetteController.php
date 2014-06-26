<?php

namespace Dof\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Dof\GraphicsBundle\EntityLook;
use Dof\GraphicsBundle\EntityLookTransforms;
use Dof\CharactersBundle\Entity\Breed;
use Dof\CharactersBundle\Entity\Face;
use Dof\ItemsBundle\Entity\SkinnedEquipmentTemplate;

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
                'look' => $look,
                'breed' => null,
                'face' => null,
                'equipments' => [ ],
                'unknowns' => [ ],
                'colors' => ($look === null) ? [ ] : $look->getColors()
            ];
        }, $req->request->has('addresses') ? $req->request->get('addresses') : [ ]);
        $skins = array();
        foreach ($results as $result)
            if ($result->look !== null)
                $skins += array_flip($result->look->getSkins());
        $skins = array_flip($skins);
        $dm = $this->getDoctrine()->getManager();
        $skins = $dm->getRepository('DofItemsBundle:SkinnedEquipmentTemplate')->findBySkinIds($skins)
            + $dm->getRepository('DofCharactersBundle:Breed')->findBySkinIds($skins)
            + $dm->getRepository('DofCharactersBundle:Face')->findBySkinIds($skins);
        foreach ($results as $result) {
            if ($result->look !== null) {
                foreach ($result->look->getSkins() as $skin) {
                    if (isset($skins[$skin])) {
                        $obj = $skins[$skin];
                        if ($obj instanceof Breed)
                            $result->breed = $obj;
                        elseif ($obj instanceof Face)
                            $result->face = $obj;
                        elseif ($obj instanceof SkinnedEquipmentTemplate)
                            $result->equipments[] = $obj;
                    } else
                        $result->unknowns[] = $skin;
                }
            }
        }
        return $this->render('DofGraphicsBundle:Pipette:index.html.twig', [
            'results' => $results
        ]);
    }
}
