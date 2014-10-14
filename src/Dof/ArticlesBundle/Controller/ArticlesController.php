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
      switch ($type) {
        case 'tutorial':
            $typeNb = ArticleType::TUTORIAL;
          break;
        case 'quest':
            $typeNb = ArticleType::QUEST;
          break;
        case 'dungeon':
            $typeNb = ArticleType::DUNGEON;
          break;  

        default:
            $typeNb = ArticleType::NEWS;
          break;
      }

      if($typeNb != $article->getType())
        return $this->redirect($this->generateUrl('dof_articles_view', array('slug' => $article->getSlug())));

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

      switch ($type) {
              case 'tutorial':
                  $typeNb = ArticleType::TUTORIAL;
                break;
              case 'quest':
                  $typeNb = ArticleType::QUEST;
                break;
              case 'dungeon':
                  $typeNb = ArticleType::DUNGEON;
                break;  

              default:
                  $typeNb = ArticleType::NEWS;
                break;
      }
 
      $newArticle = new Article();
      $newArticle = clone $article;
      $request = $this->get('request');
      /*if ($request->getMethod() != 'POST') {
        $newArticle->setNameFr($article->getNameFr());
        $newArticle->setDescriptionFr($article->getDescriptionFr());
        $newArticle->setType($article->getType());
        $newArticle->setCategory($article->getCategory());
        $newArticle->setPublished(false);
        $newArticle->setKeys($article->getKeys());
        $newArticle->addOriginalArticle($article);
      }*/

      

      if($typeNb != $article->getType())
        return $this->redirect($this->generateUrl('dof_articles_edit', array('id' => $article->getId())));

      $form = $this->createForm('dof_articlesbundle_article', $newArticle);

      if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {

          $article->AddEdit($newArticle);
          $em = $this->getDoctrine()->getManager();
          $em->persist($newArticle);
          $em->persist($article);
          $em->flush();

          return $this->render('DofArticlesBundle:Article:success.html.twig', array('type' =>$type, 'action'=>'editer'));
        }
      }

      return $this->render('DofArticlesBundle:Article:edit.html.twig', array(
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

          $article->AddEdit($article);
          $em = $this->getDoctrine()->getManager();
          $em->persist($article);
          $em->flush();

          return $this->render('DofArticlesBundle:Article:success.html.twig', array('type' =>$type, 'action'=>'ajouter'));
        }
      }

      return $this->render('DofArticlesBundle:Article:add.html.twig', array(
        'type' =>$type,'article' => $article,
        'form' => $form->createView()
      ));
    }
}
