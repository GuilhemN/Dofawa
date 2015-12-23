<?php

namespace Dof\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XN\Annotations as Utils;
use XN\Common\AjaxControllerTrait;
use XN\Common\DateFormat;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("has_role('IS_AUTHENTICATED_REMEMBERED')")
 */
class NotificationController extends Controller
{
    use AjaxControllerTrait;

    public function menuAction()
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $unread = $repo->countUnread($user);

        return $this->render('DofMainBundle:Notification:menu.html.twig', ['unread' => $unread]);
    }

    public function ajaxAction()
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $notifications = $repo->findBy(
            array('owner' => $user),
            array('createdAt' => 'DESC'),
            6
        );

        foreach ($notifications as $notification) {
            $notification->setIsRead(true);
        }
        $em->flush();

        $unread = $repo->countUnread($user);

        return $this->createJsonResponse([
            'content' => $this->renderView('DofMainBundle:Notification:ajax.html.twig', ['notifications' => $notifications]),
            'unread' => (int) $unread,
        ]);
    }

    public function checkUnreadAction()
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $notifications = $repo->findBy(
            array('owner' => $user, 'isRead' => false),
            array('createdAt' => 'ASC'),
            10
        );
        $unread = $repo->countUnread($user);

        return $this->createJsonResponse([
            'unread' => (int) $unread,
            'notifications' => $this->transform($notifications),
        ]);
    }

    public function showAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofMainBundle:Notification');

        $notifications = $repo->findBy(
            array('owner' => $this->getUser()),
            array('createdAt' => 'DESC')
        );

        foreach ($notifications as $notification) {
            $notification->setIsRead(true);
        }
        $em->flush();

        return $this->render('DofMainBundle:Notification:showAll.html.twig', ['notifications' => $notifications]);
    }

    private function transform(array $notifications)
    {
        $return = [];
        $twig = $this->get('twig');
        $translator = $this->get('translator');
        foreach ($notifications as $n) {
            $template = 'DofMainBundle:Notification-Template:'.($n->getType() !== null ? $n->getType() : 'simple').'.html.twig';
            try {
                $tpl = $twig->loadTemplate($template);
            } catch (\Exception $e) {
                if ($e instanceof \InvalidArgumentException) {
                    $tpl = $twig->loadTemplate('DofMainBundle:Notification-Template:default.html.twig');
                } else {
                    throw $e;
                }
            }
            $context = ['notification' => $n, 'ent' => $n->getEntity()];
            $return[] = array(
                'id' => $n->getId(),
                'isRead' => $n->isRead(),
                'message' => $tpl->renderBlock('title', $context),
                'path' => $tpl->renderBlock('path', $context),
                'createdAt' => DateFormat::formatDate($translator, $n->getCreatedAt()),
            );
        }

        return $return;
    }
}
