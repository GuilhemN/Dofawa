<?php

namespace Dof\Bundle\UserBundle\Controller;

use Dof\Bundle\UserBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RegistrationController extends FOSRestController
{
    /**
     * Creates a new user.
     *
     * @ApiDoc()
     *
     * @RequestParam(name="username", description="Username", strict=true)
     * @RequestParam(name="email", description="User email")
     * @RequestParam(name="password", description="User password")
     */
    public function registerAction(ParamFetcher $paramFetcher)
    {
        $fields = $paramFetcher->all();
        $manager = $this->get('fos_user.user_manager');

        $user = $manager->createUser();
        $user->setUsername($fields['username']);
        $user->setEmail($fields['email']);
        $user->setPlainPassword($fields['password']);
        $user->setEnabled(true);

        $errors = $this->get('validator')->validate($user, null, ['Registration']);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string) $errors);
        }

        $manager->updateUser($user, true); // Update and flush the user


        $context = new Context();
        $context->addGroups(['user']);
        return $this->view($user)->setSerializationContext($context);
    }
}
