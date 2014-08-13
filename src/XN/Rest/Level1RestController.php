<?php
namespace XN\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Persistence\ObjectManager;

use \Traversable;

use XN\Common\AjaxControllerTrait;

abstract class Level1RestController extends Controller
{
    use AjaxControllerTrait;

    public function fetchAction($l1id)
    {
        $this->preFetch($l1id);
        $this->unlockSession();
        $dm = $this->getDoctrine()->getManager();
        $repo = $dm->getRepository(static::ENTITY_NAME);
        if (static::USE_SLUG)
            $ent = $repo->findOneBy([ 'slug' => $l1id ]);
        else
            $ent = $repo->find($l1id);
        if ($ent === null)
            throw $this->createNotFoundException();
        $this->inFetch($dm, $l1id, $ent);
        $data = $ent->exportData(true, $this->get('translator')->getLocale());
        $this->postFetch($dm, $l1id, $ent);
        return $this->createJsonResponse($data);
    }

    public function storeAction(Request $req, $l1id)
    {
        $this->preStore($req, $l1id);
        $this->unlockSession();
        $dm = $this->getDoctrine()->getManager();
        if ($l1id === 'new')
            $ent = $this->createEntity($req, $dm, 'new');
        else {
            $repo = $dm->getRepository(static::ENTITY_NAME);
            if (static::USE_SLUG)
                $ent = $repo->findOneBy([ 'slug' => $l1id ]);
            else
                $ent = $repo->find($l1id);
            if ($ent === null) {
                $ent = $this->createEntity($req, $dm, $l1id);
                if (static::USE_SLUG)
                    $ent->setSlug($l1id);
                else
                    $ent->setId($l1id);
            }
        }
        $locale = $this->get('translator')->getLocale();
        $this->inStorePreImport($req, $dm, $l1id, $ent);
        $ent->importData($req->request, $dm, $locale);
        $this->inStorePostImport($req, $dm, $l1id, $ent);
        $dm->persist($ent);
        $dm->flush();
        $data = $ent->exportData(true, $locale);
        if ($l1id === 'new') {
            $location = $this->get('router')->generate(static::ROUTE_NAME, [ 'l1id' => static::USE_SLUG ? $ent->getSlug() : $ent->getId() ], true);
            $this->postStore($req, $dm, $l1id, $ent);
            return $this->createJsonResponse($data, 201, [
                'Location' => $location
            ]);
        } else {
            $this->postStore($req, $dm, $l1id, $ent);
            return $this->createJsonResponse($data);
        }
    }

    public function deleteAction($l1id)
    {
        $this->preDelete($l1id);
        $this->unlockSession();
        $dm = $this->getDoctrine()->getManager();
        $repo = $dm->getRepository(static::ENTITY_NAME);
        if (static::USE_SLUG)
            $ent = $repo->findOneBy([ 'slug' => $l1id ]);
        else
            $ent = $repo->find($l1id);
        if ($ent === null)
            throw $this->createNotFoundException();
        $this->inDelete($dm, $l1id, $ent);
        $dm->remove($ent);
        $dm->flush();
        $this->postDelete($dm, $l1id, $ent);
        return new Response('', 204);
    }

    protected function preFetch($l1id) { }
    protected function inFetch(ObjectManager $dm, $l1id, $ent) { }
    protected function postFetch(ObjectManager $dm, $l1id, $ent) { }

    protected function preStore(Request $req, $l1id) { }
    protected function inStorePreImport(Request $req, ObjectManager $dm, $l1id, $ent) { }
    protected function inStorePostImport(Request $req, ObjectManager $dm, $l1id, $ent) { }
    protected function postStore(Request $req, ObjectManager $dm, $l1id, $ent) { }

    protected function preDelete($l1id) { }
    protected function inDelete(ObjectManager $dm, $l1id, $ent) { }
    protected function postDelete(ObjectManager $dm, $l1id, $ent) { }

    protected abstract function createEntity(Request $req, ObjectManager $dm, $l1id);
}
