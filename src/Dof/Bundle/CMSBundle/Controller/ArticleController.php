<?php

namespace Dof\Bundle\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use XN\Annotations as Utils;
use Dof\Bundle\CMSBundle\Entity\Article;
use Dof\Bundle\CMSBundle\Entity\QuestArticle;
use Dof\Bundle\CMSBundle\Entity\DungeonArticle;
use Dof\Bundle\CMSBundle\Entity\TutorialArticle;
use Dof\Bundle\CMSBundle\Entity\Proposition;

class ArticleController extends Controller
{
    public function viewAction(Article $article)
    {
        return $this->render('DofCMSBundle:Article:view.html.twig', array(
            'article' => $article,
        ));
    }

    /**
     * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
     */
    public function createAction($type, Request $request)
    {
        if ($type == 'new' && $this->get('security.context')->isGranted('ROLE_REDACTOR')) {
            $article = new Article();
        } elseif ($type == 'quest') {
            $article = new QuestArticle();
        } elseif ($type == 'dungeon') {
            $article = new DungeonArticle();
        } elseif ($type == 'tutorial') {
            $article = new TutorialArticle();
        } else {
            throw new \LogicException('not implemented');
        }

        if ($request->getMethod() === 'POST' && $request->request->has('article')) {
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->get('article');
            $this->checkArticleEdit($article, $data);

            $article
                ->setName('Nouvel article', 'fr')
                ->setName($data['name'], $request->getLocale())
                ->setDescription('Nouvel article', 'fr')
                ->setDescription($data['description'], $request->getLocale())
                ->setPublished(false);

            if ($article->isDungeonArticle()) {
                $article->setDungeon($em->getRepository('DofMonsterBundle:Monster')->find($data['options']['dungeon']));
            } elseif ($article->isQuestArticle()) {
                $article->setQuest($em->getRepository('DofQuestBundle:Quest')->find($data['options']['quest']));
            }

            $em->persist($article);
            $em->flush($article);

            return $this->render('DofCMSBundle:Article:creation-response.html.twig');
        }

        return $this->generateEditTemplate($article, $request);
    }

    /**
     * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
     */
    public function editAction(Article $article, Request $request)
    {
        if ($request->getMethod() === 'POST' && $request->request->has('article')) {
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->get('article');
            $this->checkArticleEdit($article, $data);

            $proposition = new Proposition();
            $proposition
                ->setArticle($article)
                ->setDescription($data['description'])
                ->setOptions($data['options'])
                ->setPublished(false);

            if (!empty($data['name'])) {
                $proposition->setName($data['name']);
            }

            $em->persist($proposition);
            $em->flush($proposition);

            return $this->render('DofCMSBundle:Article:edit-response.html.twig', ['proposition' => $proposition]);
        }

        return $this->generateEditTemplate($article, $request);
    }

    private function checkArticleEdit(Article $article, $data)
    {
        $em = $this->getDoctrine()->getManager();
        if (empty($data['description'])) {
            throw new \Exception('Empty description');
        }

        if ($article->isQuestArticle()) {
            if (!($quest = $em->getRepository('DofQuestBundle:Quest')->find($data['options']['quest'])) or ($quest->getArticle() !== null && $quest->getArticle() !== $article)) {
                throw new \Exception('Non-existant quest or article already existant');
            }
        } elseif ($article->isDungeonArticle()) {
            if (empty($data['options']['roomsCount'])) {
                throw new \Exception('Needs the rooms count');
            } elseif (!($dungeon = $em->getRepository('DofMonsterBundle:Dungeon')->find($data['options']['dungeon']))) {
                throw new \Exception('Non-existant dungeon');
            }
        }
        // elseif($article->isTutorialArticle()){ }
        elseif (!$article->isTutorialArticle()) {
            if (empty($data['name'])) {
                throw new \Exception('Empty name');
            }

            if ($article->isTutorialArticle() or $article->isCollection()) {
                throw new \LogicException('Not implemented');
            }
        }
    }

    private function generateEditTemplate(Article $article, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($article->isQuestArticle()) {
            $params = ['quests' => $em->getRepository('DofQuestBundle:Quest')->findAllWithArticles($request->getLocale())];
        } elseif ($article->isDungeonArticle()) {
            $params = ['dungeons' => $em->getRepository('DofMonsterBundle:Dungeon')->findBy([], ['name'.ucfirst($request->getLocale()) => 'ASC'])];
        } else {
            // if($article->isTutorialArticle() or $article->isCollection())
            $params = [];
        }

        return $this->render('DofCMSBundle:Article:edit.html.twig', ['article' => $article] + $params);
    }
}
