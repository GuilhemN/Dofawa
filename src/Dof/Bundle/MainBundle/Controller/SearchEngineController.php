<?php

namespace Dof\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use XN\Annotations as Utils;

class SearchEngineController extends Controller
{
    public function indexAction(Request $request)
    {
        $view = '';
        if($request->query->has('q')) {
            $opts = [
                'http' => [
                    'method'=>"GET",
                    'header'=>"Authorization: Bearer DQCNE665II22LYW3SA7TTZ7GWR7NXLBI\r\n" .
                    "Accept: application/vnd.wit.20140401+json\r\n" .
                    "Accept: application/json\r\n"
                ]
            ];
            $context = stream_context_create($opts);
            $answer = json_decode(file_get_contents('https://api.wit.ai/message?q=' . urlencode($request->query->get('q')), false, $context), true);
            $result = $answer['outcomes'][0];

            if($result['intent'] === 'search_almanax')
                $view = 'Salut je suis l\'almanax';
            elseif($result['intent'] === 'search_item')
                $view = $this->searchItem($result['entities']['item'][0]['value']);
        }

        if($view === null)
            $view = $this->notFound();

        return $this->render('DofMainBundle:SearchEngine:index.html.twig', ['answer' => $view]);
    }

    private function notFound() {
        return 'Votre question n\'a pas été compris ou rien n\'a été trouvé.';
    }

    private function searchItem($name) {
        $options = [
            'name' => $name,
        ];
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('DofItemBundle:ItemTemplate')
            ->findWithOptions($options, [], 1, null, $this->get('translator')->getLocale());

        return !empty($item) ? $this->renderView('DofItemBundle::item.html.twig', ['item' => $item[0]]) : null;
    }
}
