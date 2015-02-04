<?php

namespace Dof\Bundle\ItemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QualityDiagramController extends Controller
{
    public function indexAction()
    {
        return $this->render('DofItemBundle:QualityDiagram:index.html.twig');
    }

    public function pdfAction(Request $req)
    {
        return $this->render('DofItemBundle:QualityDiagram:pdf.html.twig');
        /*
        return new Response($this->get('knp_snappy.pdf')->getOutputFromHtml(
            $this->renderView('DofItemBundle:QualityDiagram:pdf.html.twig')), 200, [
            'Content-Type' => 'application/pdf'
        ]);
        */
    }
}
