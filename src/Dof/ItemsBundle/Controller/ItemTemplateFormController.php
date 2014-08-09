<?php
namespace Dof\ItemsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Doctrine\Common\Persistence\ObjectManager;

use XN\DataBundle\Level1AjaxFormController;

class ItemTemplateFormController extends Level1AjaxFormController
{
    const ENTITY_NAME = 'DofItemsBundle:ItemTemplate';
    const ROUTE_NAME = 'dof_items_json_itemtemplate';
    const USE_SLUG = true;

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
