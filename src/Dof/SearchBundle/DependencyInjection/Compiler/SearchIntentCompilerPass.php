<?php

namespace Dof\SearchBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class SearchIntentCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
		$taggedServices = $container->findTaggedServiceIds('dof_search.intent');
        $sm = $container->getDefinition('dof_search.search_manager');
		foreach ($taggedServices as $id => $tagAttributes) {
			// $definition = $container->getDefinition($id);
			foreach ($tagAttributes as $attributes) {
				if (isset($attributes['intent'])) {
					$sm->addMethodCall('registerTagTemplate', [
                        $attributes['intent'],
					    new Reference($attributes['service'])
					]);
				}
			}
		}
    }
}
