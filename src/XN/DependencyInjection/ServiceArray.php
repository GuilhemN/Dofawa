<?php

namespace XN\DependencyInjection;

class ServiceArray extends \ArrayObject
{
    public function __construct()
    {
        parent::__construct(func_get_args());
    }
}
