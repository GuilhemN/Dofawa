<?php

namespace Dof\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dof\ForumBundle\Entity\Forum;
use Dof\ForumBundle\Entity\Topic;
use Dof\ForumBundle\Entity\Message;

class ForumController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$categories = $em->getRepository('DofForumBundle:Category')->displayOrder()->getQuery()
        	->getResult();

        return $this->render('DofForumBundle:Forum:index.html.twig', array('categories' => $categories));
    }


    /**
   	* @ParamConverter("forum", options={"repository_method" = "getOrderByDate"})
   	*/
   	public function showForumAction(Forum $forum)
    {
        return $this->render('DofForumBundle:Forum:showForum.html.twig', ['forum' => $forum]);
    }

    /**
   	* @ParamConverter("topic")
   	*/
   	public function showTopicAction(Topic $topic)
    {
        return $this->render('DofForumBundle:Forum:showTopic.html.twig', array('topic' => $topic));
    }

    /**
   	* @ParamConverter("topic")
   	*/
   	public function addMessageAction(Topic $topic)
    {
    	$message = new Message;
		$form = $this->createFormBuilder($message)
                 ->add('content',     'textarea')
                 ->getForm();

		$request = $this->get('request');
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

		    if ($form->isValid()) {

		    	$message->topic = $topic->slug;
		    	$message->createdAt = new \Datetime;

		    	$em = $this->getDoctrine()->getManager();
		      	$em->persist($message);
		      	$em->flush();

		      	return $this->redirect($this->generateUrl('dof_forum_show_topic', array('topic' => $topic )));
		    }
		}
        return $this->render('DofForumBundle:Forum:addMessage.html.twig', array('form' => $form->createView(), 'topic' => $topic));
    }
}
