<?php

namespace Dof\Bundle\ItemBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use FOS\RestBundle\Controller\FOSRestController;
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
     * @Cache(lastmodified="slug.updatedAt()")
     */
    public function getItemAction(ItemTemplate $slug)
    {
        $item = $slug;
        $repository = $this->getRepository();
        $item = $repository->findOneBySlug($slug);
        if($item === null) {
            throw $this->createNotFoundException();
        }
        $context = new Context();
        $context->addGroups(['item', 'name', 'description', 'effects', 'file']);
        $response = $this->handleView($this->view($item)->setSerializationContext($context));
        $response->setPublic();
        $response->setLastModified($item->getUpdatedAt());
        $response->isNotModified($this->getRequest());
        return $response;
    }

    /**
     * Updates an item icon.
     *
     * @ApiDoc(
     *  resource=true,
     *  output="Dof\Bundle\ItemBundle\Entity\ItemTemplate"
     * )
     * @ParamConverter("item", options={"mappings": {"slug": "slug"}})
     * @Security("has_role('ROLE_ADMIN')")
     * @Patch("/items/{slug}/update-icon")
     */
    public function updateItemIconAction(ItemTemplate $item)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://staticns.ankama.com/dofus/www/game/items/200/'.$item->getIconId().'.png');
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($curl);
        $response = curl_getinfo($curl);
        curl_close($curl);

        if ($response['http_code'] != 200) {
            throw $this->createNotFoundException('Image non disponible');
        }

        $em = $this->getDoctrine()->getManager();
        $filename = tempnam('/tmp', 'item');
        file_put_contents($filename, $content);
        $file = new File($filename, false);

        $em = $this->getDoctrine()->getManager();
        $item->setFile($file);
        $em->flush($item);
        $items = $em->getRepository('DofItemBundle:ItemTemplate')->findBy(['iconId' => $item->getIconId()]);
        foreach ($items as $i) {
            $i->setPath($item->getPath());
        }

        $em->flush();
        $context = new Context();
        $context->addGroups(['item', 'name', 'description', 'effects']);
        return $this->view($item)->setSerializationContext($context);
    }
}
