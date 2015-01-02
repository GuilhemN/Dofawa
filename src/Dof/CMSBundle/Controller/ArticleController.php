<?php

namespace Dof\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use XN\Annotations as Utils;

use Dof\CMSBundle\Entity\Article;
use Dof\CMSBundle\Entity\QuestArticle;
use Dof\CMSBundle\Entity\DungeonArticle;
use Dof\CMSBundle\Entity\TutorialArticle;
use Dof\CMSBundle\Entity\Proposition;
use Dof\MainBundle\Entity\Notification;

class ArticleController extends Controller
{
    public function viewAction(Article $article) {
        return $this->render('DofCMSBundle:Article:view.html.twig', array(
            'article' => $article
        ));
    }

    /**
    * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
    */
    public function createAction($type, Request $request){
        if($type == 'new' && $this->get('security.context')->isGranted('ROLE_REDACTOR'))
            $article = new Article();
        elseif($type == 'quest')
            $article = new QuestArticle();
        elseif($type == 'dungeon')
            $article = new DungeonArticle();
        elseif($type == 'tutorial')
            $article = new TutorialArticle();
        else
            throw new \LogicException('not implemented');

        if($request->getMethod() === 'POST' && $request->request->has('article')){
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->get('article');
            $this->checkArticleEdit($article, $data);

            $article
                ->setName('Nouvel article', 'fr')
                ->setName($data['name'], $request->getLocale())
                ->setDescription('Nouvel article', 'fr')
                ->setDescription($data['description'], $request->getLocale())
                ->setPublished(false);

            if($article->isDungeonArticle())
                $article->setDungeon($em->getRepository('DofMonsterBundle:Monster')->find($data['options']['dungeon']));
            elseif($article->isQuestArticle())
                $article->setQuest($em->getRepository('DofQuestBundle:Quest')->find($data['options']['quest']));

            $em->persist($article);
            $em->flush($article);
            return;
        }
        return $this->generateEditTemplate($article, $request);
    }

    /**
     * @Utils\Secure("IS_AUTHENTICATED_REMEMBERED")
     */
    public function editAction(Article $article, Request $request) {
        if($request->getMethod() === 'POST' && $request->request->has('article')){
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->get('article');
            $this->checkArticleEdit($article, $data);

            $proposition = new Proposition();
            $proposition
                ->setArticle($article)
                ->setDescription($data['description'])
                ->setOptions($data['options'])
                ->setPublished(false);

            if(!empty($data['name']))
                $proposition->setName($data['name']);

            $em->persist($proposition);
            $em->flush($proposition);
            return;
        }
        return $this->generateEditTemplate($article, $request);
    }

    private function checkArticleEdit(Article $article, $data) {
        $em = $this->getDoctrine()->getManager();
        if(empty($data['description']))
            throw new \Exception('Empty description');

        if($article->isQuestArticle()){
            if(!($quest = $em->getRepository('DofQuestBundle:Quest')->find($data['options']['quest'])) or ($quest->getArticle() !== null && $quest->getArticle() !== $article))
                throw new \Exception('Non-existant quest or article already existant');
        }
        elseif($article->isDungeonArticle()){
            if(empty($data['options']['roomsCount']))
                throw new \Exception('Needs the rooms count');
            elseif(!($dungeon = $em->getRepository('DofMonsterBundle:Dungeon')->find($data['options']['dungeon'])))
                throw new \Exception('Non-existant dungeon');
        }
        else {
            if(empty($data['name']))
                throw new \Exception('Empty name');

            if($article->isTutorialArticle() or $article->isCollection())
                throw new \LogicException('Not implemented');
        }
    }

    private function generateEditTemplate(Article $article, Request $request) {
        $em = $this->getDoctrine()->getManager();
        if($article->isQuestArticle())
            $params = ['quests' => $em->getRepository('DofQuestBundle:Quest')->findAllWithArticles($request->getLocale())];
        else if($article->isDungeonArticle())
            $params = ['dungeons' => $em->getRepository('DofMonsterBundle:Dungeon')->findBy([], ['name' . ucfirst($request->getLocale()) => 'ASC'])];
        else// if($article->isTutorialArticle() or $article->isCollection())
            throw new \LogicException('Not implemented');

        return $this->render('DofCMSBundle:Article:edit.html.twig', ['article' => $article] + $params);
    }

    /**
     * @Utils\Secure("ROLE_REDACTOR")
     */
    public function validAction(Article $article) {
        $newArticle = true;
        $diffs = null;
        $original = $article->getOriginalArticle();

        $type = strtolower(ArticleType::getName($article->getType()));

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {

            $em = $this->getDoctrine()->getManager();

            if($request->get('action') == 'valider'){
                if(!empty($original)){
                    $original->setArchive(1);
                    $edits = $original->getEdits();
                    foreach ($edits as $edit)
                        $edit->setOriginalArticle($article);
                    $em->persist($original);
                }
                $article->setPublished(1);
                $em->flush();

                $notification = new Notification();
                $notification
                    ->setType('news.validated')
                    ->setOwner($article->getUpdater())
                    ->setEntity($article);
                $em->persist($notification)->flush();

                return $this->render('DofCMSBundle:Edit:success.html.twig', array('type' =>$type, 'action' => 'Validation'));
            }
            if ($request->get('action') == 'supprimer'){
                $article->setArchive(1);
                $em->flush();

                $notification = new Notification();
                $notification
                    ->setType('news.deleted')
                    ->setOwner($article->getUpdater())
                    ->setEntity($article);
                $em->persist($notification)->flush();

                return $this->render('DofCMSBundle:Edit:success.html.twig', array('type' =>$type, 'action' => 'Suppression'));
            }

        }

        if(!empty($original)) {
            $descOriginal = $original->getDescription();
            exec('echo '.escapeshellarg($descOriginal).' > /tmp/validation/original.txt');
            $descArticle = $article->getDescription();
            exec('echo '.escapeshellarg($descArticle).' > /tmp/validation/article.txt');
            $command = 'diff /tmp/validation/original.txt /tmp/validation/article.txt';
            exec($command, $diffs);
            $newArticle = false;
        }

        return $this->render('DofCMSBundle:Edit:valid.html.twig', array(
            'article' => $article,
            'diffs' => $diffs,
            'type' => $type,
            'newArticle' => $newArticle
        ));
    }

    /**
     * @Utils\Secure("ROLE_REDACTOR")
     */
    public function validatePropositionAction(Proposition $proposition) {
        $em = $this->getDoctrine()->getManager();
        $locale = $proposition->getCreatedOnLocale();
        $article = $proposition->getArticle();
        $options = $proposition->getOptions();

        if($proposition->getName() !== null)
            $article->setName($proposition->getName(), $locale);
        $article->setDescription($proposition->getDescription(), $locale);

        if($article->isQuestArticle())
            $article->setQuest($em->getRepository('DofQuestBundle:Quest')->find($options['quest']));
        elseif($article->isDungeonArticle())
            $article->setQuest($em->getRepository('DofMonsterBundle:Dungeon')->find($options['dungeon']));
        else
            throw new \LogicException('not implemented');

        $proposition->setPublished(true);
        $em->flush();

        return $this->redirect($this->generateUrl('dof_cms_show', ['slug' => $article->getSlug()]));
    }
}
