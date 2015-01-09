<?php

namespace Dof\SearchBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Dof\SearchBundle\DependencyInjection\Compiler\SearchIntentCompilerPass;

class DofSearchBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SearchIntentCompilerPass());
    }
}
