<?php

namespace Dof\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dof\ForumBundle\Entity\Forum;
use Dof\ForumBundle\Entity\Topic;
use Dof\ForumBundle\Entity\Message;
use Dof\ForumBundle\Form\MessageType;
use Dof\ForumBundle\Form\TopicType;

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
    	if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
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

		      	return $this->redirect($this->generateUrl('dof_forum_show_topic', array('slug' => $topic->getSlug())).'#message-'.$message->getId());
		    }
		}
        return $this->render('DofForumBundle:Forum:addMessage.html.twig', array('form' => $form->createView(), 'topic' => $topic));
    }

    /**
   	* @ParamConverter("forum")
   	*/
   	public function addTopicAction(Forum $forum)
    {
    	if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            throw $this->createAccessDeniedException();
    	$topic =new Topic;

    	$formtopic = $this->createForm(new TopicType, $topic);

		$request = $this->get('request');
		if ($request->getMethod() == 'POST') {
			$formtopic->bind($request);

		    if ($formtopic->isValid()) {

		    	$topic->setForum($forum);

		    	$em = $this->getDoctrine()->getManager();
		      	$em->persist($topic);
		      	$em->flush();

		      	return $this->redirect($this->generateUrl('dof_forum_show_topic', array('slug' => $topic->getSlug())));
		    }
		}
        return $this->render('DofForumBundle:Forum:addTopic.html.twig', array('formtopic' => $formtopic->createView(), 'forum' => $forum));
    }
}
