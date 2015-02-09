<?php
namespace XN\UtilityBundle\DependencyInjection;

class ServiceCircularReferenceException extends \LogicException
{
    public function __construct($id, $keys) {
        $this->message = sprintf('Référence circulaire pour "%s"', $id);
    }
}
