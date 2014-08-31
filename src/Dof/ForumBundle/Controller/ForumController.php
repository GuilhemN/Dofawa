<?php

namespace Dof\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ForumController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$categorys = $em->getRepository('DofForumBundle:Category')->findAll();

        return $this->render('DofForumBundle:index.html.twig', array('category' => $categorys));
    }

   /* public function showForumAction($forum)
    {
    	$em = $this->getDoctrine()->getManager();
    	$forums = $em->getRepository('DofForumBundle:Forum')->findBy(array('name' => $forum, 'content' => 'dddd'));
        return $this->render('DofForumBundle:Default:index.html.twig', array('name' => $name));
    }

    public function showTopicAction($topic)
    {
        return $this->render('DofForumBundle:Default:index.html.twig', array('name' => $name));
    }*/
}
