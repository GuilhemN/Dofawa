<?php

namespace Dof\Bundle\User\ItemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Annotations as Utils;
use Dof\Bundle\ItemBundle\Entity\ItemTemplate;
use Dof\Bundle\User\ItemBundle\Entity\Craft;

/**
 * @Utils\Secure("ROLE_USER")
 */
class CraftController extends Controller
{
    /**
     * @Utils\Action(name="craft")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('DofUserItemBundle:Craft');
        $crafts = $repository->findBy(['owner' => $this->getUser()], ['index' => 'ASC']);

        return $this->render('DofUserItemBundle:Craft:index.html.twig', array('crafts' => $crafts));
    }

    public function createAction(ItemTemplate $item)
    {
        $em = $this->getDoctrine()->getManager();

        $craft = new Craft();
        $craft
            ->setOwner($this->getUser())
            ->setItemTemplate($item);
        $em->persist($craft);
        $em->flush();

        return $this->redirect($this->generateUrl('dof_crafts_homepage'));
    }
}
