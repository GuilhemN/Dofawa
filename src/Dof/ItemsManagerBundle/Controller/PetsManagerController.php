<?php

namespace Dof\ItemsManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Annotations as Utils;

use Dof\ItemsBundle\Entity\PetTemplate;
use Dof\ItemsManagerBundle\Entity\Pet;

/**
 * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
 */
class PetsManagerController extends Controller
{
    public function showAction()
    {
        $repository = $this->getDoctrine()->getRepository('DofItemsManagerBundle:Pet');
        $pets = $repository->getRaisablePets($this->getUser());

        return $this->render('DofItemsManagerBundle:PetsManager:show.html.twig', array('pets' => $pets));
    }

    public function addAction(PetTemplate $item)
    {
        $iFact = $this->get('item_factory');
        $em = $this->getDoctrine()->getManager();

        $pet = $iFact->createItem($item, null, $this->getUser());
        $pet->setRaise(true);
        $pet->setSticky(true);
        $em->persist($pet);
        $em->flush();

        $repository = $this->getDoctrine()->getRepository('DofItemsManagerBundle:Pet');
        $pets = $repository->getRaisablePets($this->getUser());

        return $this->redirect($this->generateUrl('dof_items_manager_pets', array(
            'pets' => $pets
            )));
    }

    public function feedAction()
    {
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

    public function delAction(Pet $item)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('DofItemsManagerBundle:Pet');
        $pets = $repository->getRaisablePets($this->getUser());

        foreach ($pets as $pet) {
            if( ($item->getOwner() === $pet->getOwner()) && ($item->getId() == $pet->getId()) ){
                $pet->setRaise(false);
                $em->persist($pet);
                $em->flush();
                break;
            }
        }


   return $this->redirect($this->generateUrl('dof_items_manager_pets', array(
            'pets' => $pets
            )));
    }

    public function raiseAction(Pet $item)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('DofItemsManagerBundle:Pet');
        $petsRaisable = $repository->getRaisablePets($this->getUser());

        $repoItem = $this->getDoctrine()->getRepository('DofItemsManagerBundle:Item');
        $pets = $repoItem->findWithOptions(array(), $this->getUser());

        foreach ($pets as $pet) {
            if( ($item->getOwner() === $pet->getOwner()) && ($item->getId() == $pet->getId()) ){
                $pet->setRaise(true);
                $pet->setLastMeal(new \DateTime());
                $em->persist($pet);
                $em->flush();
                break;
            }
        }

        return $this->redirect($this->generateUrl('dof_items_manager_pets', array(
            'pets' => $petsRaisable
            )));
    }

    public function notifAction()
    {
        $em = $this->getDoctrine()->getManager();

        if(is_numeric($this->get('request')->get('notif'))){
            $this->getUser()->setPetsManagerNotifications($this->get('request')->get('notif'));
            $em->flush();
        }

        $repository = $this->getDoctrine()->getRepository('DofItemsManagerBundle:Pet');
        $pets = $repository->getRaisablePets($this->getUser());

         return $this->redirect($this->generateUrl('dof_items_manager_pets', array(
                'pets' => $pets
                )));
    }
}
