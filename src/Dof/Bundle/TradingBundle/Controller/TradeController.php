<?php

namespace Dof\Bundle\TradingBundle\Controller;

use Doctrine\Common\Persistence\ManagerRegistry;
use Dof\Bundle\MainBundle\GameType;
use Dof\Bundle\TradingBundle\Entity\Trade;
use Dof\Bundle\ItemBundle\Entity\ItemTemplate;
use Dof\Bundle\MainBundle\Entity\Server;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TradeController
{
    private $authorizationChecker;
    private $tokenStorage;
    private $doctrine;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage, ManagerRegistry $doctrine)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->doctrine = $doctrine;
    }

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
    public function postTradesAction(ParamFetcher $paramFetcher)
    {
        $params = $paramFetcher->all();

        $item = $this->doctrine->getRepository(ItemTemplate::class)->findOneBySlug(
            $params['item']
        );
        if ($item === null) {
            throw new NotFoundHttpException('Item not found.');
        }

        $server = $this->doctrine->getRepository(Server::class)->findOneBy([
            'slug' => $params['server'],
            'gameType' => GameType::getBasicModes(),
        ]);
        if ($server === null) {
            throw new NotFoundHttpException('Server not found.');
        }

        $user = $this->tokenStorage->getToken()->getUser();
        $canSubmit = $this->doctrine->getRepository(Trade::class)
            ->checkSubmissionSpace($user, $item, $server);

        if($canSubmit) {
            $price = $params['price'];

            $trade = new Trade();
            $trade->setPrice($price > 1000 ? round($price, -3) : round($price, -1));
            $trade->setItem($item);
            $trade->setServer($server);
            $trade->setWeight($user->getWeight());

            if($this->authorizationChecker->isGranted('ROLE_TRADE_TRUSTED')) {
                $trade->setValid(true);
            }

            $manager = $this->doctrine->getManagerForClass(Trade::class);
            $manager->persist($trade);
            $manager->flush();
        }

        return new Response(null, Response::HTTP_CREATED);
    }
}
