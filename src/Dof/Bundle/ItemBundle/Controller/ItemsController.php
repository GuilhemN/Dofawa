<?php

namespace Dof\Bundle\ItemBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Context\Context;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Dof\Bundle\ItemBundle\Entity\ItemTemplate;

class ItemsController extends FOSRestController
{
    protected function getRepository()
    {
        return $this->get('doctrine')->getRepository('DofItemBundle:ItemTemplate');
    }

    /**
     * Gets a collection of items.
     *
     * @ApiDoc(
     *  output="array<Dof\Bundle\ItemBundle\Entity\ItemTemplate>",
     *  filters={
     *      {"name"="tradeable", "dataType"="boolean"},
     *      {"name"="name", "dataType"="string"}
     *  }
     * )
     *
     * @Cache(maxage=900, public=true)
     */
    public function getItemsAction()
    {
        $options = $this->getRequest()->query->all();

        $items = $this->getRepository()->findOptions($options, [], 15);

        if (isset($options['server']) && !empty($options['server'])) {
            $server = $this->getDoctrine()->getRepository('DofMainBundle:Server')
                ->findOneBySlug($options['server']);
            if ($server === null) {
                throw $this->createNotFoundException('Server not found.');
            }
            foreach ($items as $item) {
                $item->setCurrentServer($server);
            }
        }

        $context = new Context();
        $context->addGroups(['item', 'name', 'trade']);

        return $this->view($items)->setSerializationContext($context);
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
     * @Cache(lastmodified="item.getUpdatedAt()", maxage=86400, public=true)
     */
    public function getItemAction(ItemTemplate $item)
    {
        $context = new Context();
        $context->addGroups(['item', 'name', 'description', 'effects']);

        return $this->view($item)->setSerializationContext($context);
    }
}
