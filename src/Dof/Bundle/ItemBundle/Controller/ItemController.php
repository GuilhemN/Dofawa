<?php

namespace Dof\Bundle\ItemBundle\Controller;

use EXSyst\Bundle\ApiBundle\Controller\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Context\Context;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Dof\Bundle\ItemBundle\Entity\ItemTemplate;

class ItemController extends ApiController
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
        $options = $request->query->all();

        $items = $this->getRepository()->findOptions($options, [], 15);

        $context = [
            'groups' => ['item', 'name'],
        ];

        if (isset($options['server']) && !empty($options['server'])) {
            $context['groups'][] = 'price';
            $server = $this->getDoctrine()->getRepository('DofMainBundle:Server')
                ->findOneBySlug($options['server']);
            if ($server === null) {
                throw $this->createNotFoundException('Server not found.');
            }
            foreach ($items as $item) {
                $item->setCurrentServer($server);
            }
        }

        return $this->serialize($items, $context);
    }

    /**
     * Gets an item.
     *
     * @ApiDoc(
     *  resource=true,
     *  output="Dof\Bundle\ItemBundle\Entity\ItemTemplate"
     * )
     *
     * @ParamConverter("item", options={"mappings": {"slug": "slug"}})
     * @Cache(lastmodified="item.getUpdatedAt()", maxage=86400, public=true)
     */
    public function getItemAction(ItemTemplate $item)
    {
        return $this->serialize($item, ['groups' => ['item', 'name', 'description', 'effects']]);
    }
}
