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

        $html = $this->renderView('DofMainBundle:Notification:ajax.html.twig', ['notifications' => $nm->transformNotifications($notifications)]);

        foreach($notifications as $notification)
            $notification->setIsRead(true);

        $em->flush();

        $unread = $repo->countUnread($user);

        return $this->createJsonResponse([
            'html' => $html,
            'unread' => $unread
        ]);
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
