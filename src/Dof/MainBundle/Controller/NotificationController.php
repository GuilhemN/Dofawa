<?php

namespace Dof\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Common\AjaxControllerTrait;

class NotificationController extends Controller
{
    use AjaxControllerTrait;

    public function menuAction()
    {
        $user = $this->getUser();
        if(empty($user))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $unread = $repo->countUnread($user);

        return $this->render('DofMainBundle:Notification:menu.html.twig', ['unread' => $unread]);
    }

    public function ajaxAction(){
        $user = $this->getUser();
        if(empty($user))
            throw $this->createAccessDeniedException();

        $nm = $this->get('notification_manager');

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $notifications = $repo->findBy(
            array('owner' => $user),
            array('createdAt' => 'DESC'),
            6
        );
        $tNotifications = $nm->transformNotifications($notifications);

        foreach($notifications as $notification)
            $notification->setIsRead(true);

        $em->flush();

        $unread = $repo->countUnread($user);

        return $this->createJsonResponse([
            'notifications' => $tNotifications,
            'unread' => $unread
        ]);;
    }

    public function checkUnreadAction(){
        $user = $this->getUser();
        if(empty($user))
            throw $this->createAccessDeniedException();
        
        $nm = $this->get('notification_manager');

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $notifications = $repo->findBy(
            array('owner' => $user, 'isRead' => false),
            array('createdAt' => 'ASC'),
            10
        );
        $unread = $repo->countUnread($user);

        return $this->createJsonResponse([
            'unread' => $unread,
            'notifications' => $nm->transformNotifications($notifications)
        ]);
    }

    public function showAllAction(){
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $notifications = $repo->findBy(
            array('owner' => $user),
            array('createdAt' => 'DESC')
        );

        return $this->render('DofMainBundle:Notification:showAll.html.twig', ['notifications' => $notifications]);
    }
}
