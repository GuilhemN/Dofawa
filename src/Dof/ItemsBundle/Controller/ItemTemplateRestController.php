<?php
namespace Dof\ItemsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Doctrine\Common\Persistence\ObjectManager;

use XN\Rest\Level1RestController;

class ItemTemplateRestController extends Level1RestController
{
    const ENTITY_NAME = 'DofItemsBundle:ItemTemplate';
    const ROUTE_NAME = 'dof_items_json_itemtemplate';
    const USE_SLUG = true;

    public function searchEquipmentAction(Request $req)
    {
        if (strlen(trim($req->query->get('filter'))) < 3)
            return $this->createJsonResponse([ ]);
        $dm = $this->getDoctrine()->getManager();
        $locale = $this->get('translator')->getLocale();
        $repo = $dm->getRepository('DofItemsBundle:EquipmentTemplate');
        $qb = $repo->createFilteredQueryBuilder('e', $req, $locale);
        $repo->orderByText($qb, 'e', $locale);
        return $this->createJsonResponse(array_map(function ($equip) use ($locale) {
            return $equip->exportData(false, $locale);
        }, $qb->getQuery()->getResult()));
    }

    protected function preStore(Request $req, $l1id)
    {
        throw new AccessDeniedException();
    }
    protected function preDelete($l1id)
    {
        throw new AccessDeniedException();
    }

    protected function createEntity(Request $req, ObjectManager $dm, $l1id)
    {
        throw new \Exception();
    }
}
