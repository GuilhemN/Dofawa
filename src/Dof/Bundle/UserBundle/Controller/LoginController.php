<?php

namespace Dof\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class LoginController extends Controller
{
    /**
     * Authenticate an user. (Returns a token).
     *
     * @ApiDoc(
     *      parameters={
     *          {"name"="username", "dataType"="string", "required"=true},
     *          {"name"="password", "dataType"="string", "required"=true}
     *      },
     *      statusCodes={
     *          200="Returned when successful",
     *          401="Returned when bad username or password"
     *      }
     * )
     */
    public function loginAction()
    {
    }
}
