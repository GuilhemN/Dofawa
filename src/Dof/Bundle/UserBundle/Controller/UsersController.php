<?php

namespace Dof\Bundle\UserBundle\Controller;

use Dof\Bundle\UserBundle\Entity\User;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class UsersController extends FOSRestController
{
    protected function getRepository()
    {
        return $this->get('doctrine')->getRepository('AppUserBundle:User');
    }

    /**
     * Gets an user.
     *
     * @ApiDoc(
     *  resource=true,
     *  output="App\UserBundle\Entity\User"
     * )
     *
     * @Get("/users/{slug}")
     * @ParamConverter("user", options={"mappings": {"slug": "slug"}})
     * @Cache(lastmodified="user.getUpdatedAt()", public=true)
     */
    public function getUserAction(User $user)
    {
        $context = new Context();
        $context->addGroups(['user']);

        return $this->view($user)->setSerializationContext($context);
    }
}
