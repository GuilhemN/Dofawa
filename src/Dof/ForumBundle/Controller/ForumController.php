<?php

namespace Dof\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dof\ForumBundle\Entity\Forum;
use Dof\ForumBundle\Entity\Topic;

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
   	* @ParamConverter("forum")
   	*/
   	public function showForumAction(Forum $forum)
    {
        return $this->render('DofForumBundle:Forum:showForum.html.twig', array('forum' => $forum));
    }

    /**
   	* @ParamConverter("topic")
   	*/
   	public function showTopicAction(Topic $topic)
    {
        return $this->render('DofForumBundle:Forum:showTopic.html.twig', array('topic' => $topic));
    }
}
