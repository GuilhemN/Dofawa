<?php

namespace Dof\ArticlesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dof\ArticlesBundle\Entity\Article;

class ArticlesController extends Controller
{
  /**
   * @ParamConverter("article", options={"mapping": {"id": "id"}})
   */
    public function viewAction(Articles $article)
    {
      return $this->render('DofArticlesBundle:Article:view.html.twig', array(
        'article' => $article
      ));
    }


      /**
       * @ParamConverter("article", options={"mapping": {"id": "id"}})
       */
    public function editAction(Article $article)
    {

      if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        throw new AccessDeniedException();

      $form = $this->createForm('dof_articles_main', $article);

      return $this->render('DofArticlesBundle:Article:edit.html.twig', array(
        'article' => $article,
        'form' => $form->createView()
      ));
    }
}
