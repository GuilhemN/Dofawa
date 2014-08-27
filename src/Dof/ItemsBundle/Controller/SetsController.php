<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\ItemsBundle\ItemSet;

class SetsController extends Controller
{
    public function showAction(ItemSet $set)
    {
        return $this->render('DofItemsBundle:Sets:show.html.twig', [
            'set' => $set,
            'items' => $set->getItems(),
            'combinations' => $set->getCombinations()
            ]);
    }
}
