<?php

namespace Dof\Bundle\ItemBundle\Controller;

use Dof\Bundle\MainBundle\EtagGenerator;
use Symfony\Component\HttpFoundation\Request;
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
     *      {"name"="name", "dataType"="string"},
     *      {"name"="sort", "dataType"="string"},
     *      {"name"="server", "dataType"="string"}
     *  }
     * )
     *
     * @Cache(public=true)
     */
    public function getItemsAction(Request $request)
    {
        $options = $this->getRequest()->query->all();

        $items = $this->getRepository()->findOptions($options, [], 15);

        $context = new Context();
        $context->addGroups(['item', 'name']);

        if (isset($options['server']) && !empty($options['server'])) {
            $context->addGroup('price');
            $server = $this->getDoctrine()->getRepository('DofMainBundle:Server')
                ->findOneBySlug($options['server']);
            if ($server === null) {
                throw $this->createNotFoundException('Server not found.');
            }
            foreach ($items as $item) {
                $item->setCurrentServer($server);
            }
        }

        $response = $this->handleView(
            $this->view($items)->setSerializationContext($context)
        );
        $response->setEtag(EtagGenerator::getEtag($items));
        $response->isNotModified($request);

        return $response;
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
