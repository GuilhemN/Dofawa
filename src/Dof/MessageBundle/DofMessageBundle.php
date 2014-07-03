<?php

namespace Dof\MessageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DofMessageBundle extends Bundle
{
  public function getParent()
    {
        return 'FOSMessageBundle';
    }
}
