<?php

namespace Dof\Bundle\ItemBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Context\Context;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ItemsController extends FOSRestController
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
        $item = $repository->findOneBySlug($slug);
        if($item === null) {
            throw $this->createNotFoundException();
        }
        $context = new Context();
        $context->addGroups(['item', 'name', 'description', 'effects']);
        $response = $this->handleView($this->view($item)->setSerializationContext($context));
        $response->setPublic();
        $response->setLastModified($item->getUpdatedAt());
        $response->isNotModified($this->getRequest());
        return $response;
    }
}
