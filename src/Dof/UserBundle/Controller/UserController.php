<?php
// src/Sdz/BlogBundle/Controller/BlogController.php
namespace Dof\UserBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Httpfoundation\Response;

class UserController extends Controller
{
	public function indexAction($page)
	{
		$u = $this->get('security.context')->getToken()->getUser();


		$em = $this->getDoctrine()->getManager();
		$articles = $em->getRepository('DofArticlesBundle:Articles')->findArticlesWithLimits(true);
	    // On ne sait pas combien de pages il y a
	    // Mais on sait qu'une page doit être supérieure ou égale à 1
		if( $page < 1 )
		{
		      // On déclenche une exception NotFoundHttpException
		      // Cela va afficher la page d'erreur 404 (on pourra personnaliser cette page plus tard d'ailleurs)
			throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
		}
	    // Ici, on récupérera la liste des articles, puis on la passera au template
	    // Mais pour l'instant, on ne fait qu'appeler le template


		return $this->render('DofUserBundle:User:index.html.twig', array('page'=>$page,'articles'=>$articles));
	}
}