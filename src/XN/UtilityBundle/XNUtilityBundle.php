<?php

namespace XN\UtilityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use XN\UtilityBundle\DependencyInjection\Compiler\BBCodeModulesCompilerPass;
use XN\UtilityBundle\DependencyInjection\Compiler\LazyVariablesCompilerPass;

class XNUtilityBundle extends Bundle
{
}
