<?php

namespace Dof\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    protected function renderLogin(array $data)
    {
        $vb = $this->get('variables');
        $lastUsername = $vb->has('dof_user_last_username') ? $vb->get('dof_user_last_username') : '';
        $csrfToken = $vb->get('dof_user_csrf_authenticate');

        return $this->render('FOSUserBundle:Security:login.html.twig', $data + ['last_username' => $lastUsername, 'csrf_token' => $csrfToken]);
    }

    public function moduleloginAction($module = '')
    {
        $vb = $this->get('variables');
        $lastUsername = $vb->has('dof_user_last_username') ? $vb->get('dof_user_last_username') : '';
        $csrfToken = $vb->get('dof_user_csrf_authenticate');

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
