<?php

namespace Dof\GraphicsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\GraphicsBundle\Entity\CharacterLook;

class CharacterLookEmbedController extends Controller
{
	/**
	 * @ParamConverter("user", options={"mapping": {"slug": "slug"}})
	 */
	public function dispatchAction(CharacterLook $look, $theme, $format)
	{
		if (!$this->get('templating')->exists('DofGraphicsBundle:CharacterLookEmbed:' . $theme . '.js.twig'))
			throw $this->createNotFoundException();
		switch ($format) {
			case 'html':
				return $this->render('DofGraphicsBundle:CharacterLookEmbed:index.html.twig', [ 'look' => $look, 'theme' => $theme ]);
			case 'js':
				return $this->render('DofGraphicsBundle:CharacterLookEmbed:' . $theme . '.js.twig', [ 'look' => $look ]);
			default:
				throw $this->createNotFoundException();
		}
	}
}