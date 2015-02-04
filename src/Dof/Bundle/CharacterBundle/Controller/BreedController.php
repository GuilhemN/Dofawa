<?php

namespace Dof\Bundle\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dof\Bundle\CharacterBundle\Entity\Breed;
use Dof\Bundle\CharacterBundle\Gender;
use Dof\Bundle\GraphicsBundle\BasicPCLook;

class BreedController extends Controller
{
    public function showAction(Breed $breed)
    {
    	$bpcl = new BasicPCLook();
		$bpcl->setBreed($breed);
		$bpcl->setGender(Gender::MALE);
		$bpcl->setColors($breed->getMaleDefaultColors());
		$bpcl->setFace($breed->getFaces()->first());
		$el = $bpcl->toEntityLook();

        return $this->render('DofCharacterBundle:Breed:show.html.twig', array('breed' => $breed, 'look' => $el));
    }
}
