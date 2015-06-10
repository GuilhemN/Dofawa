<?php

namespace Dof\Bundle\GraphicsBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class BPCLHydrator
{
    /**
     * @var ContainerInterface
     */
    private $di;

    /**
     * @var LivingItemFactory
     */
    private $livingItemFactory;

    /**
     * @var ChameleonDragoturkey
     */
    private $chameleonDragoturkey;

    public function __construct(ContainerInterface $di)
    {
        $this->di = $di;
    }

    private function initialize()
    {
        if ($this->livingItemFactory === null) {
            $this->livingItemFactory = $this->di->get('dof_graphics.living_item_factory');
        }
        if ($this->chameleonDragoturkey === null) {
            $this->chameleonDragoturkey = $this->di->get('dof_graphics.chameleon_dragoturkey');
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $ent = $args->getEntity();
        if ($ent instanceof BasicPCLook) {
            $this->initialize();
            $ent->translateRelations($args->getEntityManager(), $this->livingItemFactory, $this->chameleonDragoturkey);
        }
    }
}
