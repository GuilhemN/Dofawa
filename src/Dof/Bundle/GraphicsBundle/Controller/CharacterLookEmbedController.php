<?php

namespace Dof\Bundle\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\Bundle\GraphicsBundle\Entity\CharacterLook;

class CharacterLookEmbedController extends Controller
{
	/**
	 * @ParamConverter("CharacterLook", options={"mapping": {"slug": "slug"}})
	 */
	public function dispatchAction(CharacterLook $look, $theme, $format)
	{
		if (!$this->get('templating')->exists('DofGraphicsBundle:CharacterLookEmbed:' . $theme . '.js.twig'))
			throw $this->createNotFoundException();
		switch ($format) {
			case 'html':
				return $this->render('DofGraphicsBundle:CharacterLookEmbed:index.html.twig', [ 'look' => $look, 'theme' => $theme ]);
			case 'js':
        $response = new Response();
        $response->headers->set('Content-Type', 'application/javascript');
				return $this->render('DofGraphicsBundle:CharacterLookEmbed:' . $theme . '.js.twig', [ 'look' => $look ], $response);
			default:
				throw $this->createNotFoundException();
		}
	}
}
