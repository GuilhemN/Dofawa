<?php

namespace Dof\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use XN\Annotations as Utils;

use Dof\CMSBundle\Entity\Article;
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
    public function editAction(Article $article, Request $request) {
        $em = $this->getDoctrine()->getManager();
        if($request->getMethod() === 'POST' && $request->request->has('article')){
            $data = $request->request->get('article');
            if(empty($data['name']) or empty($data['description']))
                throw new \Exception('Empty title or decription');
            throw new \Exception(var_dump($data));

            if($article->isQuestArticle())
                if(!$em->getRepository('DofQuestBundle:Quest')->find($data['options']['quest']))
                    throw new \Exception('Non-existant quest');
            elseif($article->isDungeonArticle())
                if(empty($data['options']['roomCount']))
                    throw new \Exception('Non-existant quest');
                elseif(!$em->getRepository('DofMonsterBundle:Dungeon')->find($data['options']['dungeon']))
                    throw new \Exception('Non-existant dungeon');
            elseif($article->isTutorialArticle())
                throw new \LogicException('Not implemented');

            $proposition = new Proposition();
            $proposition
                ->setArticle($article)
                ->setName($data['name'])
                ->setDescription($data['description'])
                ->setOptions($options);

            $em->persist($proposition);
            $em->flush($proposition);
            return;
        }

        if($article->isQuestArticle())
            $params = ['quests' => $em->getRepository('DofQuestBundle:Quest')->findBy([], ['name' . ucfirst($request->getLocale()) => 'ASC'])];
        else if($article->isDungeonArticle())
            $params = ['dungeons' => $em->getRepository('DofMonsterBundle:Dungeon')->findBy([], ['name' . ucfirst($request->getLocale()) => 'ASC'])];
        elseif($article->isTutorialArticle() or $article->isCollection())
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
}
