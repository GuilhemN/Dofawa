<?php

namespace Dof\ItemsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ItemsController extends Controller
{
    /**
     * @ParamConverter("set", class="DofItemsBundle:ItemTemplate", options={"repository_method" = "findOneWithJoins"})
     */
    public function showAction(ItemTemplate $item)
    {
        return $this->render('DofItemsBundle:Items:index.html.twig', ['item' => $item]);
    }
}
