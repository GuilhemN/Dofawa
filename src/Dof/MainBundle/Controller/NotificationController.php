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

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $unreadNotifications = $repo->countUnread($user);

        return $this->render('DofMainBundle:Notification:menu.html.twig', ['unread' => $unreadNotifications]);
    }

    public function ajaxAction(){
        $user = $this->get('security.context')->getToken()->getUser();
        $nm = $this->get('notification_manager');

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $notifications = $repo->findBy(
            array('owner' => $user),
            array('createdAt' => 'DESC'),
            6
            );

        $response = $this->createJsonResponse([
            'notifications' => $nm->transformNotifications($notifications),
            'unread' => $unread
        ]);

        foreach($notifications as $notification)
            $notification->setIsRead(true);

        $em->flush();

        $unread = $repo->countUnread($user);

        return $response;
    }

    public function checkUnreadAction(){
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');
        $unread = $repo->countUnread($user);

        return $this->createJsonResponse([
            'unread' => $unread
        ]);
    }
}
