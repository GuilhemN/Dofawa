<?php

namespace Dof\Bundle\TradingBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Dof\Bundle\TradingBundle\Entity\Trade;

class TradingsController extends FOSRestController
{
    /**
     * Sets a price for a given item.
     *
     * @ApiDoc(
     *  resource=true
     * )
     *
     * @RequestParam(name="price", requirements="[0-9]+", description="price")
     * @RequestParam(name="item", requirements="[a-zA-Z0-9\-]+", description="Item slug")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function postTradingsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('DofItemBundle:ItemTemplate')->findOneBySlug($request->request->get('item'));
        if ($item === null) {
            throw $this->createNotFoundException('Item not found.');
        }

        $trade = new Trade();
        $trade->setPrice($request->request->get('price'));
        $trade->setItem($item);

        $em->persist($trade);
        $em->flush();

        return $this->view(null, 201);
    }
}
