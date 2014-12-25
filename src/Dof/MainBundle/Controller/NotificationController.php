<?php

namespace Dof\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Common\AjaxControllerTrait;
use XN\Common\DateFormat;

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

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $notifications = $repo->findBy(
            array('owner' => $user),
            array('createdAt' => 'DESC'),
            6
        );

        foreach($notifications as $notification)
            $notification->setIsRead(true);
        $em->flush();

        $unread = $repo->countUnread($user);

        return $this->createJsonResponse([
            'notifications' => $this->transform($notifications),
            'unread' => $unread
        ]);;
    }

    public function checkUnreadAction(){
        $user = $this->getUser();
        if(empty($user))
            throw $this->createAccessDeniedException();

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
            'content' => $this->renderView('DofMainBundle:Notification:ajax.html.twig', ['notifications' => $notifications])
        ]);
    }

    public function showAllAction(){
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $notifications = $repo->findBy(
            array('owner' => $this->getUser()),
            array('createdAt' => 'DESC')
        );

        foreach($notifications as $notification)
            $notification->setIsRead(true);
        $em->flush();

        return $this->render('DofMainBundle:Notification:showAll.html.twig', ['notifications' => $notifications]);
    }

    private function transform(array $notifications) {
        $return = [];
        $translator = $this->get('translator');
        foreach($notifications as $n){
            $template = 'DofMainBundle:Notification-Template:' . ($n->getType() !== null ? $n->getType() : 'simple') . '.html.twig';
            $context = ['notification' => $n, 'ent' => $n->getEntity()];
            $return[] = array(
                'isRead' => $n->isRead(),
                'message' => $this->get('twig')->loadTemplate($template)->renderBlock('title', $context),
                'path' => $this->get('twig')->loadTemplate($template)->renderBlock('path', $context),
                'createdAt' => DateFormat::formatDate($translator, $n->getCreatedAt())
            );
        }
        return $return;
    }
}
