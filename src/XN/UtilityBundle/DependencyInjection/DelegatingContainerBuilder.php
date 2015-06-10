<?php

namespace XN\UtilityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;

class DelegatingContainerBuilder extends ContainerBuilder
{
    public function hasDefinition($id)
    {
        if (parent::hasDefinition($id)) {
            return true;
        }

        if (false !== ($pos = strpos($id, '\\'))) {
            // Proceed with a delegate container
            if (!($delegate = $this->get($delegateId = substr($id, 0, $pos), ContainerInterface::NULL_ON_INVALID_REFERENCE))) {
                return false;
            }
            $childId = substr($id, $pos + 1);
            $this->setDefinition(strtolower($id), new Definition(
                    method_exists($delegate, 'getClassOf') ? $delegate->getClassOf($childId) : 'stdClass',
                    [$childId]))
                ->setFactoryService($delegateId)
                ->setFactoryMethod('get');

            return true;
        }

        return false;
    }
}
