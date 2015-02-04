<?php

namespace Dof\GraphicsBundle;

use Doctrine\Common\Persistence\ObjectManager;

use Dof\ItemBundle\Entity\ItemTemplate;
use Dof\ItemBundle\Entity\ItemTemplateRepository;
use Dof\ItemBundle\Entity\ItemTypeRepository;

class LivingItemFactory
{
    /**
     * @var ItemTemplateRepository
     */
    private $itemTemplates;
    /**
     * @var ItemTypeRepository
     */
    private $itemTypes;

    public function __construct(ObjectManager $dm)
    {
        $this->itemTemplates = $dm->getRepository('DofItemBundle:ItemTemplate');
        $this->itemTypes = $dm->getRepository('DofItemBundle:ItemType');
    }

    public function createFromSkin($skin)
    {
        if (is_array($skin)) {
            $result = [ ];
            foreach ($skin as $sk) {
                $res = $this->createFromSkin($sk);
                if ($res !== null)
                    $result[$sk] = $res;
            }
            return $result;
        }
        $types = LivingItem::getTypes();
        foreach (LivingItem::getBaseSkins() as $id => $bskin)
            if ($skin >= $bskin + 1 && $skin <= $bskin + 20)
                return new LivingItem($this->itemTemplates->find($id), $this->itemTypes->find($types[$id]), $skin - $bskin);
        return null;
    }

    public function createFromTemplateAndLevel(ItemTemplate $template, $level)
    {
        $types = LivingItem::getTypes();
        return new LivingItem($template, $this->itemTypes->find($types[$template->getId()]), $level);
    }
}
