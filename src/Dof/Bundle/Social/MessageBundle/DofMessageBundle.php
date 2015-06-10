<?php

namespace Dof\Bundle\Social\MessageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DofMessageBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSMessageBundle';
    }
}
