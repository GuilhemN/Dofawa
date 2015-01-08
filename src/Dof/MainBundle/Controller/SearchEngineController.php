<?php

namespace Dof\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use XN\Annotations as Utils;

class SearchEngineController extends Controller
{
    public function indexAction(Request $request)
    {
        $view = '';
        if($request->request->has('q')) {
            $opts = [
                'http' => [
                    'method'=>"GET",
                    'header'=>"Authorization: Bearer DQCNE665II22LYW3SA7TTZ7GWR7NXLBI\r\n" .
                    "Accept: application/vnd.wit.20140401+json\r\n" .
                    "Accept: application/json\r\n"
                ]
            ];
            $context = stream_context_create($opts);
            $answer = json_decode(file_get_contents('https://api.wit.ai/message?q=' . urlencode($request->request->get('q')), false, $context), true);
            $result = $answer['outcomes'][0];

            if($result['intent'] === 'search_almanax')
                $view = 'Salut je suis l\'almanax';
            elseif($result['intent'] === 'search_item')
                $view = $this->searchItem($result['entities']['item']['value']);
        }

        return $this->render('DofMainBundle:SearchEngine:index.html.twig', ['answer' => $view]);
    }

    private function searchItem($name) {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('DofItemsBundle:ItemTemplate')->findBy(['name' . ucfirst($request->getLocale()) => $name]);

        return $this->renderView('DofItemsBundle::item.html.twig', ['item' => $item]);
    }
}
