<?php

namespace Dof\ArticlesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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


      /**
       * @ParamConverter("articles", options={"mapping": {"id": "id"}})
       */
    public function editAction(Articles $article)
    {

      if (!$this->get('security.context')->isGranted('ROLE_USER_FULL'))
        throw new AccessDeniedException();

      $form = $this->createForm('dof_articles_main', $article);

      return $this->render('DofArticlesBundle:Article:edit.html.twig', array(
        'article' => $article,
        'form' => $form->createView()
      ));
    }
}
