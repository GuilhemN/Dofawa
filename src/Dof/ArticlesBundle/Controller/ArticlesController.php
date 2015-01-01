<?php

namespace Dof\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use XN\Annotations as Utils;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dof\CMSBundle\Entity\Article;

use Dof\MainBundle\Entity\Notification;

class ArticlesController extends Controller
{
    public function viewAction(Article $article)
    {
        return $this->render('DofCMSBundle:Article:view.html.twig', array(
            'article' => $article
        ));
    }

    public function editAction(Article $article) {

    }

    /**
     * @Utils\Secure("ROLE_REDACTOR")
     */
    public function validAction(Article $article)
    {
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
