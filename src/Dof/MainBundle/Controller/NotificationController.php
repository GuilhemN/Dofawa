<?php

namespace Dof\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Common\AjaxControllerTrait;

class NotificationController extends Controller
{
    use AjaxControllerTrait;

    public function menuAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $nm = $this->get('notification_manager');

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $notifications = $repo->findBy(
            array('owner' => $user),
            array('createdAt' => 'DESC'),
            10
            );

        $unread = 0;
        foreach($notifications as $notification)
            if(!$notification->isRead())
                $unread++;

        return $this->render('DofMainBundle:Notification:menu.html.twig', ['notifications' => $nm->transformNotifications($notifications), 'unread' => $unread]);
    }

    public function markAsReadAction(){
        $securityContext = $this->get('security.context');
        if(!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $user = $securityContext->getToken()->getUser();

        $notifications = $repo->findBy(array('owner' => $user, 'isRead' => false));

        $i = 0;
        foreach($notifications as $notification){
            $notification->setIsRead(true);
            $em->persist($notification);

            $i++;
        }

        $return = array('updated' => $i);
        $em->flush();

        return $this->createJsonResponse($return);
    }
}
