<?php

namespace Dof\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Httpfoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use XN\Annotations as Utils;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{


    public function moduleloginAction($module = '')
    {
        $pb = $this->get('xn.parameter_bag');
        $lastUsername = $pb->getParameter('dof_userlast_username');
        $csrfToken = $pb->getParameter('dof_usercsrf_authenticate');

        return $this->renderModuleLogin(array(
                'last_username' => $lastUsername,
                'csrf_token' => $csrfToken,
            ),
            $module
        );
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderModuleLogin(array $data, $module = '')
    {
        $template = 'DofUserBundle:Security:loginmodule.html.twig';

        return $this->container->get('templating')->renderResponse($template, $data);
    }
}
