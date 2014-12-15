<?php
namespace XN\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;

class LazyServiceBox
{
    private $container;
    private $name;
    private $service;

    public function __construct(ContainerInterface $container, $name)
    {
        $this->container = $container;
        $this->name = $name;
        $this->service = null;
    }

    public function unwrap()
    {
        if (!$this->service)
            $this->service = $this->container->get($this->name);
        return $this->service;
    }
}
