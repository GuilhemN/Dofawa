<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Dof\ItemsBundle\Entity\ItemSet;

class SetsController extends Controller
{
    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('DofItemsBundle:ItemSet');

        $sets = $repo->findWithJoins();

        return $this->render('DofItemsBundle:Sets:index.html.twig', [
            'sets' => $sets
            ]);
    }
    
    /**
     * @ParamConverter("set", class="DofItemsBundle:ItemSet", options={"repository_method" = "findOneWithJoins"})
     */
    public function showAction(ItemSet $set)
    {
        $items = $set->getItems();

        $maxLevel = 0;
        foreach($items as $item){
            if($item->getLevel() > $maxLevel)
                $maxLevel = $item->getLevel();
        }

        return $this->render('DofItemsBundle:Sets:show.html.twig', [
            'set' => $set,
            'level' => $maxLevel,
            'items' => $items,
            'items_count' => count($items),
            'combinations' => $set->getCombinations()
            ]);
    }
}
