<?php
namespace XN\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;

class LazyServiceBox
{
    private $container;
    private $id;
	private $invalidBehavior;
    private $service;

    public function __construct(ContainerInterface $container, $id, $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)
    {
        $this->container = $container;
        $this->id = $id;
		$this->invalidBehavior = $invalidBehavior;
        $this->service = null;
    }

    public function unwrap()
    {
        if (!$this->service)
            $this->service = $this->container->get($this->id, $this->invalidBehavior);
        return $this->service;
    }
}
