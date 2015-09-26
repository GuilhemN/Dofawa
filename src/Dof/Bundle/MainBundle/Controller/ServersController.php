<?php

namespace Dof\Bundle\MainBundle\Controller;

use Dof\Bundle\MainBundle\GameType;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class ServersController extends FOSRestController
{
    protected function getRepository()
    {
        return $this->get('doctrine')->getRepository('DofMainBundle:Server');
    }

    /**
     * Gets a collection of servers.
     *
     * @ApiDoc(
     *  output="array<Dof\Bundle\MainBundle\Entity\Server>"
     * )
     *
     * @Get("/servers")
     * @Cache(maxage=3600, public=true)
     */
    public function getServersAction()
    {
        $items = $this->getRepository()->findBy(['visible' => true, 'gameType' => GameType::getBasicModes()]);
        $context = new Context();
        $context->addGroups(['server', 'name']);

        return $this->view($items)->setSerializationContext($context);
    }
}
