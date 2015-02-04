<?php

namespace Dof\Bundle\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ModulesController extends Controller
{
    public function moduleRightAction($type)
    {
        if($type != 'news' and $type != 'tutoriels')
            throw new HttpException(500, "Error in the sended type to the module of the recent articles. It must be 'tutoriels' or 'news'. Please check the value in the page who called ".__FILE__.".");

        $trad = 'menuright.'.$type;

        if($type == 'news')
            $modType = null;
        else
            $modType = ['dungeon', 'quest', 'tutorial'];

        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('DofCMSBundle:Article')->findArticlesWithLimits($modType, 0, 11);

        return $this->render('DofCMSBundle:Modules:news.html.twig', array(
            'articles' => $articles,
            'trad' => $trad
        ));
    }
}
