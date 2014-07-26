<?php
namespace XN\DataBundle;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Persistence\ObjectManager;

use \Traversable;

use XN\UtilityBundle\AjaxControllerTrait;

abstract class Level2AjaxFormController extends Controller
{
    use AjaxControllerTrait;

    public function fetchAction($l1id, $l2id)
    {
        $this->preFetch($l1id, $l2id);
        $this->unlockSession();
        $dm = $this->getDoctrine()->getManager();
        $ent = $dm->getRepository(static::ENTITY_NAME)->find($l2id);
        if ($ent === null)
            throw $this->createNotFoundException();
        $this->checkParents($dm, $l1id, $l2id, $ent);
        $this->inFetch($dm, $l1id, $l2id, $ent);
        $data = $ent->exportData();
        $this->postFetch($dm, $l1id, $l2id, $ent);
        return $this->createJsonResponse($data);
    }

    public function storeAction(Request $req, $l1id, $l2id)
    {
        $this->preStore($req, $l1id, $l2id);
        $this->unlockSession();
        $dm = $this->getDoctrine()->getManager();
        if ($l2id === 'new')
            $ent = $this->createEntity($req, $dm, $l1id, 'new');
        else {
            $ent = $dm->getRepository(static::ENTITY_NAME)->find($l2id);
            if ($ent === null) {
                $ent = $this->createEntity($req, $dm, $l1id, $l2id);
                $ent->setId($l2id);
            } else
                $this->checkParents($dm, $l1id, $l2id, $ent);
        }
        $this->inStorePreImport($req, $dm, $l1id, $l2id, $ent);
        $ent->importData($req->request, $dm);
        $this->inStorePostImport($req, $dm, $l1id, $l2id, $ent);
        $dm->persist($ent);
        $dm->flush();
        $data = $ent->exportData();
        if ($l1id === 'new') {
            $location = $this->get('router')->generate(static::ROUTE_NAME, [ 'l1id' => $l1id, 'l2id' => $ent->getId() ], true);
            $this->postStore($req, $dm, $l1id, $l2id, $ent);
            return $this->createJsonResponse($data, 201, [
                'Location' => $location
            ]);
        } else {
            $this->postStore($req, $dm, $l1id, $l2id, $ent);
            return $this->createJsonResponse($data);
        }
    }

    public function deleteAction($l1id, $l2id)
    {
        $this->preDelete($l1id, $l2id);
        $this->unlockSession();
        $dm = $this->getDoctrine()->getManager();
        $ent = $dm->getRepository(static::ENTITY_NAME)->find($l2id);
        if ($ent === null)
            throw $this->createNotFoundException();
        $this->checkParents($dm, $l1id, $l2id, $ent);
        $this->inDelete($dm, $l1id, $l2id, $ent);
        $dm->remove($ent);
        $dm->flush();
        $this->postDelete($dm, $l1id, $l2id, $ent);
        return new Response('', 204);
    }

    protected function preFetch($l1id, $l2id) { }
    protected function inFetch(ObjectManager $dm, $l1id, $l2id, $ent) { }
    protected function postFetch(ObjectManager $dm, $l1id, $l2id, $ent) { }

    protected function preStore(Request $req, $l1id, $l2id) { }
    protected function inStorePreImport(Request $req, ObjectManager $dm, $l1id, $l2id, $ent) { }
    protected function inStorePostImport(Request $req, ObjectManager $dm, $l1id, $l2id, $ent) { }
    protected function postStore(Request $req, ObjectManager $dm, $l1id, $l2id, $ent) { }

    protected function preDelete($l1id, $l2id) { }
    protected function inDelete(ObjectManager $dm, $l1id, $l2id, $ent) { }
    protected function postDelete(ObjectManager $dm, $l1id, $l2id, $ent) { }

    protected abstract function createEntity(Request $req, ObjectManager $dm, $l1id, $l2id);
    protected function checkParents(ObjectManager $dm, $l1id, $l2id, $ent) { }
}
