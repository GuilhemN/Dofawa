<?php

namespace Dof\Bundle\MainBundle\Controller;

use Dof\Bundle\MainBundle\GameType;
use EXSyst\Bundle\ApiBundle\Controller\ApiController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class ServersController extends ApiController
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
     * @Cache(maxage=3600, public=true)
     */
    public function getServersAction()
    {
        $items = $this->getRepository()
            ->findBy(['visible' => true, 'gameType' => GameType::getBasicModes()], ['name' => 'ASC']);

        return $this->serialize($items, ['groups' => ['server', 'name']]);
    }
}
