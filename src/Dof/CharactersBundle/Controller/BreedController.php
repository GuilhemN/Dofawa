<?php

namespace Dof\CharactersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\CharactersBundle\Entity\Breed;
use Dof\CharactersBundle\Gender;
use Dof\GraphicsBundle\BasicPCLook;

class BreedController extends Controller
{
    public function showAction(Breed $breed)
    {
    	$bpcl = new BasicPCLook();
		$bpcl->setBreed($breed);
		$bpcl->setGender(Gender::MALE);
		$bpcl->setColor(1, 15066084);
		$bpcl->setColor(2, 8036512);
		$bpcl->setColor(3, 10641706);
		$bpcl->setColor(4, 2722985);
		$bpcl->setColor(5, 14207124);
		$bpcl->setFace($breed->getFaces()->first())
		$el = $bpcl->toEntityLook();

        return $this->render('DofCharactersBundle:Breed:show.html.twig', array('breed' => $breed, 'look' => $el));
    }
}
