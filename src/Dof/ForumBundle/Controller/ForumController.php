<?php

namespace Dof\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ForumController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$categories = $em->getRepository('DofForumBundle:Category')->findAll();

        return $this->render('DofForumBundle:Forum:index.html.twig', array('categories' => $categories));
    }


    /**
   	* @ParamConverter("forumslug", options={"mapping": {"slug": "slug"}})
   	*/
   	public function showForumAction($forumslug)
    {
    	$em = $this->getDoctrine()->getManager();
    	$forum = $em->getRepository('DofForumBundle:Forum')->findbySlug($forumslug));
        return $this->render('DofForumBundle:Forum:showForum.html.twig', array('forum' => $forum));
    }

    /*public function showTopicAction($topic)
    {
        return $this->render('DofForumBundle:Default:index.html.twig', array('name' => $name));
    }*/
}
