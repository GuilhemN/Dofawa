<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QualityDiagramController extends Controller
{
    public function indexAction()
    {
        return $this->render('DofItemsBundle:QualityDiagram:index.html.twig');
    }

    public function pdfAction(Request $req)
    {
        return $this->render('DofItemsBundle:QualityDiagram:pdf.html.twig');
        /*
        return new Response($this->get('knp_snappy.pdf')->getOutputFromHtml(
            $this->renderView('DofItemsBundle:QualityDiagram:pdf.html.twig')), 200, [
            'Content-Type' => 'application/pdf'
        ]);
        */
    }
}
