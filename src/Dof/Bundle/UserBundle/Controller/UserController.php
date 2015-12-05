<?php

namespace Dof\Bundle\UserBundle\Controller;

use Dof\Bundle\UserBundle\Entity\User;
use EXSyst\Bundle\ApiBundle\Controller\ApiController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class UserController extends ApiController
{
    protected function getRepository()
    {
        return $this->get('doctrine')->getRepository('DofUserBundle:User');
    }

    /**
     * Gets an user.
     *
     * @ApiDoc(
     *  resource=true,
     *  output="App\UserBundle\Entity\User"
     * )
     *
     * @ParamConverter("user", options={"mappings": {"slug": "slug"}})
     * @Cache(lastmodified="user.getUpdatedAt()", public=true)
     */
    public function getUserAction(User $user)
    {
        return $this->serialize($user, ['groups' => ['user']]);
    }
}
