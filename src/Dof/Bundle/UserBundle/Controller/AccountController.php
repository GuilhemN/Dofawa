<?php

namespace Dof\Bundle\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Annotations as Utils;

use FOS\UserBundle\Model\UserInterface;

use XN\Security\TOTPGenerator;
use XN\UtilityBundle\TOTPAuthenticationListener;
use XN\Common\AjaxControllerTrait;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    use AjaxControllerTrait;

    public function securityAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface)
            throw $this->createAccessDeniedException();

        return $this->render('DofUserBundle:Account:security.html.twig', ['user' => $user]);
    }

    /**
     * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
     * @Utils\UsesSession
     */
    public function doubleAuthAction(){
        $user = $this->getUser();

        $secret = TOTPGenerator::genSecret();

        $session = $this->get('session');
        $session->start();
        $session->set('totp_secret', $secret);

        return $this->render('DofUserBundle:Account:doubleAuth.html.twig', ['secret' => $secret]);
    }


    /**
    * @Utils\UsesSession
    */
    public function checkTotpAction(){
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface)
            return $this->createJsonResponse([
                'success' => false,
                'error' => 'not_connected'
                ]);

        $totp = trim($this->get('request')->request->get('_totp'));
        $session = $this->get('session');
        $session->start();

        if($session->has('totp_secret')){
            $key = $session->get('totp_secret');

    		$stamp = TOTPAuthenticationListener::getCurrentStamp();
            $totp = intval($totp);
    		if (TOTPAuthenticationListener::hash($stamp + 1, $key) == $totp)
    			$totpStamp = $stamp + 1;
    		elseif (TOTPAuthenticationListener::hash($stamp, $key) == $totp)
    			$totpStamp = $stamp;
    		elseif (TOTPAuthenticationListener::hash($stamp - 1, $key) == $totp)
    			$totpStamp = $stamp - 1;
    		else
    			$totpStamp = null;

    		if ($totpStamp !== null && ($user->getTOTPLastSuccessStamp() === null || $totpStamp > $user->getTOTPLastSuccessStamp())){
                $user->setTOTPSecretKey($key);
                $this->getDoctrine()->getManager()->flush($user);

                $response = [
                    'success' => true
                ];
            }
            else
                $response = [
                    'success' => false,
                    'error' => 'bad_totp'
                ];
        }
        else
            $response = [
                'success' => false,
                'error' => 'not_set'
            ];

        return $this->createJsonResponse($response);
    }

    public function checkHasDoubleAuthAction(){
        $em = $this->getDoctrine()->getManager();
        $username = $this->get('request')->request->get('_username');

        $user = $em->getRepository('DofUserBundle:User')->findOneBy(array('username' => $username));

        if($user === null or $user->getTotpSecretKey() == (null or 0))
            return new Response('0');

        return new Response('1');
    }
}
