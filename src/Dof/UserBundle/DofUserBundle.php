<?php

namespace Dof\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DofUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
