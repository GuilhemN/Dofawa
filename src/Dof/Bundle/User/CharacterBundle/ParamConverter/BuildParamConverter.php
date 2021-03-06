<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dof\Bundle\User\CharacterBundle\ParamConverter;

use Dof\Bundle\User\CharacterBundle\BuildManager;
use Dof\Bundle\User\CharacterBundle\Entity\Stuff;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class BuildParamConverter implements ParamConverterInterface
{
    private $buildManager;

    public function __construct(BuildManager $buildManager) {
        $this->buildManager = $buildManager;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \LogicException       When unable to guess how to get a Doctrine instance from the request information
     * @throws NotFoundHttpException When object not found
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $name = $configuration->getName();
        $class = $configuration->getClass();

        if (!$request->attributes->has('user') or
            !$request->attributes->has('character') or
            !$request->attributes->has('stuff')
            ) {
            throw new \LogicException('Paramètres manquants dans la route pour récupérer le playerCharacter (doit contenir user, character et stuff).');
        }

        $stuff = $this->buildManager->getBySlugs(
                    $request->attributes->get('user'),
                    $request->attributes->get('character'),
                    $request->attributes->get('stuff')
                );

        if (null === $stuff) {
            throw new NotFoundHttpException('Build non trouvé.');
        }

        $request->attributes->set($configuration->getName(), $stuff);
        $request->attributes->set('character', $character = $stuff->getCharacter());
        $request->attributes->set('user', $user = $character->getOwner());

        $request->attributes->set('canSee', $stuff->canSee());
        $request->attributes->set('canWrite', $stuff->canWrite());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        return $configuration->getName() === 'stuff' && "Dof\Bundle\User\CharacterBundle\Entity\Stuff" === $configuration->getClass();
    }
}
