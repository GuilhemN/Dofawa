<?php

namespace Dof\Bundle\ItemBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Context\Context;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Dof\Bundle\ItemBundle\Entity\ItemTemplate;
use Symfony\Component\HttpFoundation\File\File;


class ItemsController extends FOSRestController
{
    protected function getRepository() {
        return $this->get('doctrine')->getRepository('DofItemBundle:ItemTemplate');
    }

    /**
     * Gets an item.
     *
     * @ApiDoc(
     *  resource=true,
     *  output="Dof\Bundle\ItemBundle\Entity\ItemTemplate"
     * )
     *
     * @Get("/items/{slug}")
     * @ParamConverter("item", options={"mappings": {"slug": "slug"}})
     * @Cache(lastmodified="item.getUpdatedAt()", public=true)
     */
    public function getItemAction(ItemTemplate $item)
    {
        $context = new Context();
        $context->addGroups(['item', 'name', 'description', 'effects', 'file']);
        $response = $this->handleView($this->view($item)->setSerializationContext($context));
        $response->setPublic();
        $response->setLastModified($item->getUpdatedAt());
        $response->isNotModified($this->getRequest());
        return $response;
    }
}
