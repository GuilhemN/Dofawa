<?php

namespace Dof\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function indexAction(Request $request)
    {
        if($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
            throw new \Exception($this->get('selected_character')->find()->getEntity()->getName());
        }
        $sm = $this->get('dof_search.search_manager');
        $output = $sm->process($request->query->get('q'));
        return $this->render('DofSearchBundle:Search:index.html.twig', ['answer' => $output]);
    }
}
