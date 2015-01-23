<?php
namespace XN\UtilityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;

use XN\DependencyInjection\LazyServiceBox;

class LazyDelegateContainer
{
	private $container;
	private $cache;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
		$this->cache = [ ];
	}

	public function has($id)
	{
		return $this->container->has($id);
	}

	public function getClassOf($id)
	{
		return 'XN\DependencyInjection\LazyServiceBox';
	}

	public function get($id, $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)
	{
		$key = $id . '@' . $invalidBehavior;
		if (!isset($this->cache[$key]))
			$this->cache[$key] = new LazyServiceBox($this->container, $id, $invalidBehavior);
		return $this->cache[$key];
	}
}
