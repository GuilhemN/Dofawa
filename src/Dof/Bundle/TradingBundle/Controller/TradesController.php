<?php

namespace Dof\Bundle\TradingBundle\Controller;

use Dof\Bundle\MainBundle\GameType;
use Dof\Bundle\TradingBundle\Entity\Trade;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class TradesController extends FOSRestController
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
     *
     * @POST("/trades")
     */
    public function postTradingsAction(ParamFetcher $paramFetcher)
    {
        $params = $paramFetcher->all();
        $em = $this->getDoctrine()->getManager();

        $item = $em->getRepository('DofItemBundle:ItemTemplate')->findOneBySlug(
            $params['item']
        );
        if ($item === null) {
            throw $this->createNotFoundException('Item not found.');
        }

        $server = $em->getRepository('DofMainBundle:Server')->findOneBy([
            'slug' => $params['server'],
            'gameType' => GameType::getBasicModes(),
        ]);
        if ($server === null) {
            throw $this->createNotFoundException('Server not found.');
        }

        $canSubmit = $em->getRepository('DofTradingBundle:Trade')
            ->checkSubmissionSpace($this->getUser(), $item, $server);

        if($canSubmit) {
            $price = $params['price'];

            $trade = new Trade();
            $trade->setPrice($price > 1000 ? round($price, -3) : round($price, -1));
            $trade->setItem($item);
            $trade->setServer($server);
            $trade->setWeight($this->getUser()->getWeight());

            if($this->get('security.authorization_checker')->isGranted('ROLE_TRADE_TRUSTED')) {
                $trade->setValid(true);
            }

            $em->persist($trade);
            $em->flush();
        }

        return $this->view(null, 201);
    }
}
