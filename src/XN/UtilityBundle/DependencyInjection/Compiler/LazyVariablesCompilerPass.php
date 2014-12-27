<?php

namespace XN\UtilityBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class LazyVariablesCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('variables'))
            return;

        $definition = $container->getDefinition('variables');

        $taggedServices = $container->findTaggedServiceIds('variable');
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
				if (isset($attributes['getter']))
					$definition->addMethodCall('setLazy', [
						$attributes['key'],
						new Reference('service_container'),
						$id,
						$attributes['getter']
					]);
				else
					$definition->addMethodCall('setLazy', [
						$attributes['key'],
						new Reference('service_container'),
						$id
					]);
			}
		}
    }
}
