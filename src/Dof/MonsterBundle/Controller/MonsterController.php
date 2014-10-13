<?php

namespace Dof\MonsterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dof\MonsterBundle\Entity\Monster;

class MonsterController extends Controller
{
    public function showAction(Monster $monster)
    {
        return $this->render('DofMonsterBundle:Monster:show.html.twig', array('monster' => $monster));
    }
}
