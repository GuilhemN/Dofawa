<?php

namespace Dof\ItemsManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dof\ItemsBundle\Entity\ItemTemplate;

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

    public function addAction()
    {
    	if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();

        return $this->render('DofItemsManagerBundle:PetsManager:add.html.twig', array('name' => $name));
    }

    public function feedAction()
    {
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('DofItemsManagerBundle:Pet');
        $pets = $repository->getRaisablePets($this->getUser());
        $petsFeed = $repository->findBy(['id' => $this->get('request')->get('pets'), 'owner' => $this->getUser()]);

        foreach ($petsFeed as $pet)
            if($pet->isRaise())
                $pet->setLastMeal(date('Y-m-d H:i:s'));
                
        $em->flush();

        return $this->render('DofItemsManagerBundle:PetsManager:show.html.twig', array('pets' => $pets));
    }
}