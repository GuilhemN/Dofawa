<?php

namespace Dof\Bundle\User\ItemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Annotations as Utils;

use Dof\Bundle\ItemBundle\Entity\PetTemplate;
use Dof\Bundle\User\ItemBundle\Entity\Pet;

/**
 * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
 */
class PetsManagerController extends Controller
{
    /**
     * @Utils\Action(name="pets-manager")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('DofUserItemBundle:Pet');
        $pets = $repository->findBy(['owner' => $this->getUser(), 'raise' => true], ['nextFeeding' => 'ASC']);

        return $this->render('DofUserItemBundle:PetsManager:index.html.twig', array('pets' => $pets));
    }

    public function addAction(PetTemplate $item)
    {
        $iFact = $this->get('item_factory');
        $em = $this->getDoctrine()->getManager();

        $pet = $iFact->createItem($item, null, $this->getUser());
        $pet->setRaise(true)
            ->setSticky(true)
            ->setLastFeeding(new \DateTime('now'))
            ->setLastNotification(new \DateTime('now'));
        $em->persist($pet);
        $em->flush();

        return $this->createRedirection();
    }

    public function feedAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('DofUserItemBundle:Pet');
        $petsFeed = $repository->findBy(['id' => $this->get('request')->get('pets'), 'owner' => $this->getUser(), 'raise' => true]);

        $lastFeeding = new \DateTime();
        foreach ($petsFeed as $pet)
            $pet->setLastFeeding($lastFeeding);

        $em->flush();

        return $this->createRedirection();
    }

    public function removeAction(Pet $pet)
    {
        if($pet->getOwner() !== $this->getUser())
            throw $this->createAccessDeniedException();

        $pet->setRaise(false);
        $this->getDoctrine()->getManager()->flush();

        return $this->createRedirection();
    }

    public function raiseAction(Pet $pet)
    {
        if($pet->getOwner() !== $this->getUser())
            throw $this->createAccessDeniedException();
        if(!$pet->isRaise())
            throw $this->createNotFoundException();

        $pet->setRaise(true);
        $pet->setLastFeeding(new \DateTime());
        $em->flush();

        return $this->createRedirection();
    }

    public function notifAction()
    {
        $this->getUser()->setPetsManagerNotifications((bool) $this->get('request')->get('notif'));
        $this->getDoctrine()->getManager()->flush();

        return $this->createRedirection();
    }

    protected function createRedirection(){
        return $this->redirect($this->generateUrl('dof_items_manager_pets'));
    }
}
