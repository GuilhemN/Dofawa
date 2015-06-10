<?php

namespace XN\UtilityBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class BBCodeModulesCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $modules = [];

        $taggedServices = $container->findTaggedServiceIds('xn.bbcode.publish');
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                if (isset($attributes['module'])) {
                    $module = $attributes['module'];
                    if (!isset($modules[$module])) {
                        $modules[$module] = [];
                    }
                    $modules[$module][] = $id;
                }
            }
        }

        $taggedServices = $container->findTaggedServiceIds('xn.bbcode.use');
        foreach ($taggedServices as $id => $tagAttributes) {
            $definition = $container->getDefinition($id);
            foreach ($tagAttributes as $attributes) {
                if (isset($attributes['module'])) {
                    $module = $attributes['module'];
                    if (isset($modules[$module])) {
                        foreach ($modules[$module] as $service) {
                            $definition->addMethodCall('registerTagTemplate', [
                                new Reference($service),
                            ]);
                        }
                    }
                } elseif (isset($attributes['service'])) {
                    $definition->addMethodCall('registerTagTemplate', [
                        new Reference($attributes['service']),
                    ]);
                }
            }
        }
    }
}
