<?php

namespace XN\Common;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceWithContainer
{
    protected $di;

    public function __construct(ContainerInterface $di)
    {
        $this->di = $di;
    }

    protected function getEntityManager()
    {
        return $this->di->get('doctrine.orm.default_entity_manager');
    }
}
