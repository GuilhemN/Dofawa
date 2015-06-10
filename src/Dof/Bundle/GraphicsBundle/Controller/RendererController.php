<?php

namespace Dof\Bundle\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RendererController extends Controller
{
    public function lookAction($look, $focus, $direction, $width, $height, $padding)
    {
        // FIXME : This proxy is only TEMPORARY !!!!!
        $response = new Response(file_get_contents('http://staticns.ankama.com/dofus/renderer/look/'.$look.'/'.$focus.'/'.$direction.'/'.$width.'_'.$height.'-'.$padding.'.png'));
        $response->headers->set('Content-Type', 'image/png');

        return $response;
    }
}
