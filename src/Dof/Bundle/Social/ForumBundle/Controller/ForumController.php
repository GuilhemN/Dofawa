<?php

namespace Dof\Bundle\Social\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use XN\Annotations as Utils;

use Dof\Bundle\Social\ForumBundle\Entity\Forum;
use Dof\Bundle\Social\ForumBundle\Entity\Topic;
use Dof\Bundle\Social\ForumBundle\Entity\Message;

use Dof\Bundle\Social\ForumBundle\Form\MessageTopicType;
use Dof\Bundle\Social\ForumBundle\Form\MessageType;

use Dof\Bundle\MainBundle\Entity\Badge;
use Dof\Bundle\MainBundle\BadgeType;
use Dof\Bundle\UserBundle\Entity\Badge as UserBadge;

class ForumController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$categories = $em->getRepository('DofForumBundle:Category')->findBy([], ['index' => 'ASC']);

        return $this->render('DofForumBundle:Forum:index.html.twig', [ 'categories' => $categories ]);
    }

    /**
     * @ParamConverter("forum", options={"repository_method" = "getOrderByDate"})
     */
    public function showForumAction(Forum $forum)
    {
        return $this->render('DofForumBundle:Forum:showForum.html.twig', [ 'forum' => $forum, 'user' => $this->getUser() ]);
    }

    public function showTopicAction(Topic $topic)
    {
        if($this->getUser() !== null && !$topic->isReadBy($this->getUser()))
        {
            $topic->addReadBy($this->getUser());
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('DofForumBundle:Forum:showTopic.html.twig', [ 'topic' => $topic ]);
    }

    /**
     * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
     * @Utils\UsesSession
     */
    public function addMessageAction(Topic $topic)
    {
        if($topic->getLocked() && !$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
            throw $this->createAccessDeniedException();

        $message = new Message;
        $form = $this->createForm(new MessageType, $message);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {

                $message->setTopic($topic);

                $em = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();

                // Badge
                $this->get('badge_manager')->addBadge('forum-message');

                return $this->redirect($this->generateUrl('dof_forum_show_topic', array('slug' => $topic->getSlug())) .'#message-' . $message->getId());
            }
        }
        return $this->render('DofForumBundle:Forum:addMessage.html.twig', array('form' => $form->createView(), 'topic' => $topic));
    }

    /**
     * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
     * @Utils\UsesSession
     */
    public function addTopicAction(Forum $forum)
    {
        $topic = new Topic;
        $message = new Message;

        $message->setTopic($topic);
        $topic->addMessage($message);

        $formTopic = $this->createForm(new MessageTopicType, $message);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $formTopic->bind($request);

            if ($formTopic->isValid()) {

                $topic->setForum($forum);
                $topic->setLocked(false);

                $em = $this->getDoctrine()->getManager();
                $em->persist($topic);
                $em->persist($message);
                $em->flush();

                return $this->redirect($this->generateUrl('dof_forum_show_topic', array('slug' => $topic->getSlug())));
            }
        }
        return $this->render('DofForumBundle:Forum:addTopic.html.twig', array('formtopic' => $formTopic->createView(), 'forum' => $forum));
    }
}