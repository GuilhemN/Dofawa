<?php
namespace Dof\Common;

use XN\Graphics\ColorPseudoRepository;
use XN\Common\NullPseudoRepository;

trait PseudoRepositoriesTrait
{
    public function getPseudoRepository($type)
    {
        switch ($type)
        {
            case 'color':
                return new ColorPseudoRepository();
            default:
                return new NullPseudoRepository();
        }
    }
}
