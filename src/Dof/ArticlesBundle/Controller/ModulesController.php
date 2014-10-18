<?php

namespace Dof\ArticlesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ModulesController extends Controller
{
    public function moduleRightAction($type)
    {
        if($type != 'news' and $type != 'tutoriels')
            throw new HttpException(500, "Error in the sended type to the module of the recent articles. It must be 'tutoriels' or 'news'. Please check the value in the page who called ".__FILE__.".");

    	$trad='menuright.'.$type;

      if($type == 'news')
        $modType = 4;
      else
        $modType = "1 and a.type = 2 and a.type = 3";

    	$em = $this->getDoctrine()->getManager();
    	$articles = $em->getRepository('DofArticlesBundle:Article')->findArticlesWithLimits($modType, 0, 11);

        return $this->render('DofArticlesBundle:Modules:news.html.twig', array(
          'articles' => $articles,
          'trad' => $trad
        ));
    }
}
