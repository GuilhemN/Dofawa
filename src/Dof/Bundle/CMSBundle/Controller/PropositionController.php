<?php

namespace Dof\Bundle\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use XN\Annotations as Utils;

use Dof\Bundle\CMSBundle\Entity\Article;
use Dof\Bundle\CMSBundle\Entity\QuestArticle;
use Dof\Bundle\CMSBundle\Entity\DungeonArticle;
use Dof\Bundle\CMSBundle\Entity\TutorialArticle;
use Dof\Bundle\CMSBundle\Entity\Proposition;
use Dof\Bundle\MainBundle\Entity\Notification;

class PropositionController extends Controller
{
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $propositions = $em->getRepository('DofCMSBundle:Proposition')->findAll();

        return $this->render('DofCMSBundle:Proposition:index.html.twig', ['propositions' => $propositions]);
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
    public function validateAction(Proposition $proposition) {
        if($proposition->isPublished())
            throw $this->createNotFoundException();

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
