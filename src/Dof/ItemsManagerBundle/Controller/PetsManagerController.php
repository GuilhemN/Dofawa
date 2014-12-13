<?php

namespace Dof\ItemsManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PetsManagerController extends Controller
{
    public function showAction()
    {
    	if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();

        $repository = $this->getDoctrine()->getRepository('DofItemsManagerBundle:Pet');
        $pets = $repository->getRaisablePets($this->getUser());

        return $this->render('DofItemsManagerBundle:PetsManager:show.html.twig', array('pets' => $pets));
    }

    public function addAction($id)
    {
    	if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();
        $iFact = $this->get('item_factory');
        $em = $this->getDoctrine()->getManager();

        $item = $em->getRepository('DofItemsBundle:PetTemplate')->find($id);

        if($item->isPet())
        {
            $pet = $iFact->createItem($item, null, $this->getUser());
            $pet->setRaise(true);
            $pet->setSticky(true);
            $em->persist($iFact);
            $em->flush();
        }

        $repository = $this->getDoctrine()->getRepository('DofItemsManagerBundle:Pet');
        $pets = $repository->getRaisablePets($this->getUser());

        return $this->redirect($this->generateUrl('dof_items_manager_pets', array(
                'pets' => $pets
                )));
    }

    public function feedAction()
    {
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('DofItemsManagerBundle:Pet');
        $pets = $repository->getRaisablePets($this->getUser());
        $petsFeed = $repository->findBy(['id' => $this->get('request')->get('pets'), 'owner' => $this->getUser()]);

        $date = new \DateTime();
        $date->format('Y-m-d H:i:s');
        foreach ($petsFeed as $pet)
            if($pet->isRaise())
                $pet->setLastMeal($date);

        $em->flush();

         return $this->redirect($this->generateUrl('dof_items_manager_pets', array(
                'pets' => $pets
                )));
    }
}
