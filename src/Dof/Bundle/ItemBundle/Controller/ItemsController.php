<?php

namespace Dof\Bundle\ItemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Dof\Bundle\ItemBundle\Entity\ItemTemplate;
use Dof\Bundle\ItemBundle\ItemSlot;
use Dof\Bundle\User\CharacterBundle\Entity\PlayerCharacter;
use Dof\Bundle\User\CharacterBundle\Entity\Stuff;
use Dof\Bundle\UserBundle\Entity\User;

class ItemsController extends Controller
{
    protected function getRepository() {
        return $this->get('doctrine')->getRepository('DofItemBundle:ItemTemplate');
    }

    /**
     * Gets an item
     *
     * @ApiDoc(
     *  resource=true,
     *  output="Dof\Bundle\ItemBundle\Entity\ItemTemplate"
     * )
     */
    public function getItemAction($slug)
    {
        $repository = $this->getRepository();
        $item = $repository->findOneBySlug();
        if($item === null) {
            throw $this->createNotFoundException();
        }
        return $item;
    }
}
