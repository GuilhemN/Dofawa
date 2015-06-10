<?php

namespace XN\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use XN\Common\AjaxControllerTrait;

abstract class Level3RestController extends Controller
{
    use AjaxControllerTrait;

    public function fetchAction($l1id, $l2id, $l3id)
    {
        $this->preFetch($l1id, $l2id, $l3id);
        $this->unlockSession();
        $dm = $this->getDoctrine()->getManager();
        $repo = $dm->getRepository(static::ENTITY_NAME);
        if (static::USE_SLUG) {
            $ent = $repo->findOneBy(['slug' => $l3id]);
        } else {
            $ent = $repo->find($l3id);
        }
        if ($ent === null) {
            throw $this->createNotFoundException();
        }
        $this->checkParents($dm, $l1id, $l2id, $l3id, $ent);
        $this->inFetch($dm, $l1id, $l2id, $l3id, $ent);
        $data = $ent->exportData(true, $this->get('translator')->getLocale());
        $this->postFetch($dm, $l1id, $l2id, $l3id, $ent);

        return $this->createJsonResponse($data);
    }

    public function storeAction(Request $req, $l1id, $l2id, $l3id)
    {
        $this->preStore($req, $l1id, $l2id, $l3id);
        $this->unlockSession();
        $dm = $this->getDoctrine()->getManager();
        if ($l3id === 'new') {
            $ent = $this->createEntity($req, $dm, $l1id, $l2id, 'new');
        } else {
            $repo = $dm->getRepository(static::ENTITY_NAME);
            if (static::USE_SLUG) {
                $ent = $repo->findOneBy(['slug' => $l3id]);
            } else {
                $ent = $repo->find($l3id);
            }
            if ($ent === null) {
                $ent = $this->createEntity($req, $dm, $l1id, $l2id, $l3id);
                if (static::USE_SLUG) {
                    $ent->setSlug($l3id);
                } else {
                    $ent->setId($l3id);
                }
            } else {
                $this->checkParents($dm, $l1id, $l2id, $l3id, $ent);
            }
        }
        $locale = $this->get('translator')->getLocale();
        $this->inStorePreImport($req, $dm, $l1id, $l2id, $l3id, $ent);
        $ent->importData($req->request, $dm, $locale);
        $this->inStorePostImport($req, $dm, $l1id, $l2id, $l3id, $ent);
        $dm->persist($ent);
        $dm->flush();
        $data = $ent->exportData(true, $locale);
        if ($l1id === 'new') {
            $location = $this->get('router')->generate(static::ROUTE_NAME, ['l1id' => $l1id, 'l2id' => $l2id, 'l3id' => static::USE_SLUG ? $ent->getSlug() : $ent->getId()], true);
            $this->postStore($req, $dm, $l1id, $l2id, $l3id, $ent);

            return $this->createJsonResponse($data, 201, [
                'Location' => $location,
            ]);
        } else {
            $this->postStore($req, $dm, $l1id, $l2id, $l3id, $ent);

            return $this->createJsonResponse($data);
        }
    }

    public function deleteAction($l1id, $l2id, $l3id)
    {
        $this->preDelete($l1id, $l2id, $l3id);
        $this->unlockSession();
        $dm = $this->getDoctrine()->getManager();
        $repo = $dm->getRepository(static::ENTITY_NAME);
        if (static::USE_SLUG) {
            $ent = $repo->findOneBy(['slug' => $l3id]);
        } else {
            $ent = $repo->find($l3id);
        }
        if ($ent === null) {
            throw $this->createNotFoundException();
        }
        $this->checkParents($dm, $l1id, $l2id, $l3id, $ent);
        $this->inDelete($dm, $l1id, $l2id, $l3id, $ent);
        $dm->remove($ent);
        $dm->flush();
        $this->postDelete($dm, $l1id, $l2id, $l3id, $ent);

        return new Response('', 204);
    }

    protected function preFetch($l1id, $l2id, $l3id)
    {
    }
    protected function inFetch(ObjectManager $dm, $l1id, $l2id, $l3id, $ent)
    {
    }
    protected function postFetch(ObjectManager $dm, $l1id, $l2id, $l3id, $ent)
    {
    }

    protected function preStore(Request $req, $l1id, $l2id, $l3id)
    {
    }
    protected function inStorePreImport(Request $req, ObjectManager $dm, $l1id, $l2id, $l3id, $ent)
    {
    }
    protected function inStorePostImport(Request $req, ObjectManager $dm, $l1id, $l2id, $l3id, $ent)
    {
    }
    protected function postStore(Request $req, ObjectManager $dm, $l1id, $l2id, $l3id, $ent)
    {
    }

    protected function preDelete($l1id, $l2id, $l3id)
    {
    }
    protected function inDelete(ObjectManager $dm, $l1id, $l2id, $l3id, $ent)
    {
    }
    protected function postDelete(ObjectManager $dm, $l1id, $l2id, $l3id, $ent)
    {
    }

    abstract protected function createEntity(Request $req, ObjectManager $dm, $l1id, $l2id, $l3id);
    protected function checkParents(ObjectManager $dm, $l1id, $l2id, $l3id, $ent)
    {
    }
}
