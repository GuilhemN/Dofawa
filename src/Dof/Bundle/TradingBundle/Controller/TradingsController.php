<?php

namespace Dof\Bundle\TradingBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Request\ParamFetcher;
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
     * @RequestParam(name="price", requirements="[0-9]+", description="price", strict=true)
     * @RequestParam(name="item", requirements="[a-zA-Z0-9\-]+", description="Item slug", strict=true)
     * @RequestParam(name="server", requirements="[a-zA-Z0-9\-]+", description="Item slug", strict=true)
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function postTradingsAction(ParamFetcher $paramFetcher)
    {
        $params = $paramFetcher->all();
        $em = $this->getDoctrine()->getManager();

        $item = $em->getRepository('DofItemBundle:ItemTemplate')->findOneBySlug($params['item']);
        if ($item === null) {
            throw $this->createNotFoundException('Item not found.');
        }

        $server = $em->getRepository('DofMainBundle:Server')->findOneBySlug($params['server']);
        if ($server === null) {
            throw $this->createNotFoundException('Server not found.');
        }

        $trade = new Trade();
        $trade->setPrice($params['price']);
        $trade->setItem($item);
        $trade->setServer($server);

        $em->persist($trade);
        $em->flush();

        return $this->view(null, 201);
    }
}
