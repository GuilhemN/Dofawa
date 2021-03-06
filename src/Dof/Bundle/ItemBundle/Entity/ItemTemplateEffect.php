<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Persistence\ObjectManager;
use XN\Rest\ExportableInterface;
use XN\Rest\ImportableTrait;
use XN\Persistence\IdentifiableInterface;
use Dof\Bundle\CharacterBundle\EffectInterface;
use Dof\Bundle\CharacterBundle\EffectTrait;

/**
 * ItemTemplateEffect.
 *
 * @ORM\Table(name="dof_item_template_effects")
 * @ORM\Entity(repositoryClass="ItemTemplateEffectRepository")
 */
class ItemTemplateEffect implements IdentifiableInterface, ExportableInterface, EffectInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    use ImportableTrait, EffectTrait;

    /**
     * @var ItemTemplate
     *
     * @ORM\ManyToOne(targetEntity="ItemTemplate", inversedBy="effects")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $item;

    /**
     * @var int
     *
     * @ORM\Column(name="order_", type="integer")
     */
    private $order;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set item.
     *
     * @param ItemTemplate $item
     *
     * @return ItemTemplateEffect
     */
    public function setItem(ItemTemplate $item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item.
     *
     * @return ItemTemplate
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set order.
     *
     * @param int $order
     *
     * @return ItemTemplateEffect
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function exportData($full = true, $locale = 'fr')
    {
        return [
            'effect' => $this->effectTemplate->getId(),
            'param1' => $this->param1,
            'param2' => $this->param2,
            'param3' => $this->param3,
        ] + ($full ? [
            'item' => $this->item->exportData(false, $locale),
            'order' => $this->order,
        ] : []);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        return false;
    }

    public function getDescription($locale = 'fr', $full = false)
    {
        $desc = $this->getEffectTemplate()->expandDescription([
            '1' => $this->getParam1(),
            '2' => $this->getParam2(),
            '3' => $this->getParam3(),
        ], $locale);

        return $desc;
    }

    public function __toString()
    {
        return $this->getPlainTextDescription();
    }
}
