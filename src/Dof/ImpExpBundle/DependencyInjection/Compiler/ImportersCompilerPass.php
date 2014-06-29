<?php

namespace Dof\ImpExpBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ImportersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('dof_imp_exp.import_manager'))
            return;

        $definition = $container->getDefinition('dof_imp_exp.import_manager');

        $taggedServices = $container->findTaggedServiceIds('dof_imp_exp.importer');
        foreach ($taggedServices as $id => $tagAttributes)
            foreach ($tagAttributes as $attributes)
                $definition->addMethodCall('registerDataSet', [
                    $attributes['provides'],
                    new Reference($id),
                    isset($attributes['requires']) ? (array)$attributes['requires'] : [ ]
                ]);
    }
}
