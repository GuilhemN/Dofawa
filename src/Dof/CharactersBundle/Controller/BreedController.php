<?php

namespace Dof\CharactersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\CharactersBundle\Entity\Breed;

class BreedController extends Controller
{
    public function showAction(Breed $breed)
    {
        return $this->render('DofCharactersBundle:Breed:show.html.twig', array('breed' => $breed));
    }
}
