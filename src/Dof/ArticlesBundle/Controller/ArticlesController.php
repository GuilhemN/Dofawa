<?php

namespace Dof\ArticlesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dof\ArticlesBundle\Entity\Articles;

class ArticlesController extends Controller
{
  /**
   * @ParamConverter("articles", options={"mapping": {"id": "id"}})
   */
    public function viewAction(Articles $article)
    {
      return $this->render('DofArticlesBundle:Article:view.html.twig', array(
        'article' => $article
      ));
    }
}
