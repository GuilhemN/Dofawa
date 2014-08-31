<?php

namespace Dof\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends Controller
{
    public function menuAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $notifications = $repo->findByOwner($user);

        $unread = 0;
        foreach($notifications as $notification)
            if(!$notification->isRead())
                $unread++;

        return $this->render('DofMainBundle:Notification:menu.html.twig', ['notifications' => $notifications, 'unread' => $unread]);
    }
}
