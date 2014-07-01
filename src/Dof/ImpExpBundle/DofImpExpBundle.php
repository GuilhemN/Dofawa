<?php

namespace Dof\ImpExpBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Dof\ImpExpBundle\DependencyInjection\Compiler\ImportersCompilerPass;

class DofImpExpBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ImportersCompilerPass());
    }
}
