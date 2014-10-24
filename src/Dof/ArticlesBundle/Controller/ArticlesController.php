<?php

namespace Dof\ArticlesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dof\ArticlesBundle\Entity\Article;
use Dof\ArticlesBundle\ArticleType;

class ArticlesController extends Controller
{
   /**
   * @ParamConverter("article", options={"mapping": {"slug": "slug"}})
   */
    public function viewAction($type, Article $article)
    {
      if($type != strtolower(ArticleType::getName($article->getType())))
        return $this->redirect($this->generateUrl('dof_articles_view', array('slug' => $article->getSlug(),'type'=> strtolower(ArticleType::getName($article->getType())))));

      return $this->render('DofArticlesBundle:Article:view.html.twig', array(
        'article' => $article, 'type'=>$type
      ));
    }


    /**
    * @ParamConverter("article", options={"mapping": {"id": "id"}})
    */
    public function editAction($type, Article $article)
    {

      if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        throw new AccessDeniedException();
 
      $newArticle = new Article();
      $newArticle = clone $article;
      $request = $this->get('request');

      if($type != strtolower(ArticleType::getName($article->getType())))
        return $this->redirect($this->generateUrl('dof_articles_edit', array('id' => $article->getId(),'type'=> strtolower(ArticleType::getName($article->getType())))));

      $form = $this->createForm('dof_articlesbundle_article', $newArticle);

      if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
          $newArticle->setSlug(null);
          $newArticle->setPublished(0);
          $newArticle->setOriginalArticle($article);
          $article->addEdit($newArticle);
          $em = $this->getDoctrine()->getManager();
          $em->persist($newArticle);
          $em->persist($article);
          $em->flush();

          return $this->render('DofArticlesBundle:Edit:success.html.twig', array('type' =>$type, 'action'=>'editer'));
        }
      }

      return $this->render('DofArticlesBundle:Edit:edit.html.twig', array(
        'type' =>$type,'newArticle' => $newArticle,
        'form' => $form->createView()
      ));
    }

    public function AddAction($type)
    {

      if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        throw new AccessDeniedException();

      $article = new Article;

      $form = $this->createForm('dof_articlesbundle_article', $article);

      $request = $this->get('request');
      if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
          $article->setPublished(0);
          $em = $this->getDoctrine()->getManager();
          $em->persist($article);
          $em->flush();

          return $this->render('DofArticlesBundle:Edit:success.html.twig', array('type' =>$type, 'action'=>'ajouter'));
        }
      }

      return $this->render('DofArticlesBundle:Edit:add.html.twig', array(
        'type' =>$type,'article' => $article,
        'form' => $form->createView()
      ));
    }

    public function viewTypeAction($type)
    {
      switch ($type) {
        case strtolower(ArticleType::getName(3)):
          $viewType = ArticleType::DUNGEON;
          break;

        case strtolower(ArticleType::getName(2)):
          $viewType = ArticleType::QUEST;
          break;

        case strtolower(ArticleType::getName(1)):
          $viewType = ArticleType::TUTORIAL;
          break;
        
        default:
          $viewType = ArticleType::NEWS;
          $type = strtolower(ArticleType::getName(4));
          break;
      }

      $em = $this->getDoctrine()->getManager();
      $articles = $em->getRepository('DofArticlesBundle:Article')->findArticlesWithLimits($viewType, 0, 20);

      return $this->render('DofArticlesBundle:Article:viewType.html.twig', array(
        'articles' => $articles, 'type'=>$type
      ));
    }

  public function addListAction($type,$page)
  {
    if (!$this->get('security.context')->isGranted('ROLE_REDACTOR'))
        throw new AccessDeniedException();
    $translator = $this->get('translator');
    switch ($type) {
        case strtolower(ArticleType::getName(3)):
          $viewType = ArticleType::DUNGEON;
          break;

        case strtolower(ArticleType::getName(2)):
          $viewType = ArticleType::QUEST;
          break;

        case strtolower(ArticleType::getName(1)):
          $viewType = ArticleType::TUTORIAL;
          break;
        
        default:
          $viewType = ArticleType::NEWS;
          $type = strtolower(ArticleType::getName(4));
          break;
      }

    $repository = $this->getDoctrine()->getRepository('DofArticlesBundle:Article');
    $countArticles = $repository->countTotal($viewType,0);
    $articlesPerPage = 15;
    $firstResult = ($page - 1) * $articlesPerPage;

    if($firstResult > $countArticles)
            throw $this->createNotFoundException('This page does not exist.');

    $articles = $repository->findArticlesWithLimits($viewType, $firstResult, $articlesPerPage,0);

    $pagination = array(
        'page' => $page,
        'route' => 'dof_articles_archive',
        'pages_count' => ceil($countArticles / $articlesPerPage),
        'route_params' => array()
      );

    return $this->render('DofArticlesBundle:Edit:addList.html.twig', array(
      'articles' => $articles,
      'page' => $page,
      'pagination' => $pagination,
      'type' => $type
    ));

  }

  /**
   * @ParamConverter("article", options={"mapping": {"slug": "slug"}})
   */
    public function validAction(Article $article)
    {
      if (!$this->get('security.context')->isGranted('ROLE_REDACTOR'))
        throw new AccessDeniedException();
      $newArticle = true;
      $diffs = null;
      $original = $article->getOriginalArticle();
      if(!empty($original))
      {
        $descOriginal = $original->getDescription();
        exec('echo '.escapeshellarg($descOriginal).' > /tmp/validation/original.txt');
        $descArticle = $article->getDescription();
        exec('echo '.escapeshellarg($descArticle).' > /tmp/validation/article.txt');
        $command = 'diff /tmp/validation/original.txt /tmp/validation/article.txt';
        exec($command, $diffs);
        $newArticle = false;
      }

      return $this->render('DofArticlesBundle:Edit:valid.html.twig', array(
        'article' => $article,
        'diffs' => $diffs,
        'newArticle' => $newArticle
      ));
    }
}
