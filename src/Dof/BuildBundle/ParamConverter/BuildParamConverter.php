<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dof\BuildBundle\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * DoctrineParamConverter.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class BuildParamConverter implements ParamConverterInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \LogicException       When unable to guess how to get a Doctrine instance from the request information
     * @throws NotFoundHttpException When object not found
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $name    = $configuration->getName();
        $class   = $configuration->getClass();

        if (!$request->attributes->has('user') or
            !$request->attributes->has('character') or
            !$request->attributes->has('stuff')
            )
            throw new \LogicException('Paramètres manquants dans la route pour récupérer le playerCharacter (doit contenir user, character et stuff).');

        $em = $this->getEntityManager();
        $repository = $em->getRepository('DofBuildBundle:Stuff');

        $slugs = [
            'user' => $request->attributes->get('user'),
            'character' => $request->attributes->get('character'),
            'stuff' => $request->attributes->get('stuff'),
            ];
        $stuff = $repository->findParamConverter($slugs['user'], $slugs['character'], $slugs['stuff']);

        if (null === $stuff) {
            throw new NotFoundHttpException('Build non trouvé.');
        }

        $request->attributes->set('buildStuff', $stuff);
        $request->attributes->set('character', $character = $stuff->getCharacter());
        $request->attributes->set('user', $character->getOwner());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        if (null === $configuration->getClass())
            return false;

        return "Dof\BuildBundle\Entity\Stuff" === $configuration->getClass();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->container->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @return \Symfony\Component\Security\Core\SecurityContext
     */
    protected function getSecurityContext()
    {
        return $this->container->get('security.context');
    }
}
