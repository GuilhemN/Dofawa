<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use XN\Rest\ExportableInterface;
use XN\Rest\ImportableTrait;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;
use Dof\Bundle\ItemBundle\ItemTemplateFactory;

/**
 * ItemType.
 *
 * @ORM\Table(name="dof_item_types")
 * @ORM\Entity(repositoryClass="ItemTypeRepository")
 */
class ItemType implements IdentifiableInterface, ExportableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    use TimestampableTrait, SluggableTrait, ImportableTrait, ReleaseBoundTrait, LocalizedNameTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="slot", type="integer")
     */
    private $slot;

    /**
     * @var string
     *
     * @ORM\Column(name="effect_area", type="string", length=31, nullable=true)
     */
    private $effectArea;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemTemplate", mappedBy="type")
     */
    private $items;

    /**
     * @var string
     *
     * @Groups({"item"})
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return ItemType
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set slot.
     *
     * @param int $slot
     *
     * @return ItemType
     */
    public function setSlot($slot)
    {
        $this->slot = $slot;

        return $this;
    }

    /**
     * Get slot.
     *
     * @return int
     */
    public function getSlot()
    {
        return $this->slot;
    }

    /**
     * Set effectArea.
     *
     * @param string $effectArea
     *
     * @return ItemType
     */
    public function setEffectArea($effectArea)
    {
        $this->effectArea = $effectArea;

        return $this;
    }

    /**
     * Get effectArea.
     *
     * @return string
     */
    public function getEffectArea()
    {
        return $this->effectArea;
    }

    /**
     * Add items.
     *
     * @param ItemTemplate $items
     *
     * @return ItemType
     */
    public function addItem(ItemTemplate $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items.
     *
     * @param ItemTemplate $items
     *
     * @return ItemType
     */
    public function removeItem(ItemTemplate $items)
    {
        $this->items->removeElement($items);

        return $this;
    }

    /**
     * Get items.
     *
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    public function createItemTemplate($autoAdd = true)
    {
        $item = ItemTemplateFactory::createItemTemplate($this->getSlot(), $this->getId());
        $item->setType($this);
        if ($autoAdd) {
            $this->addItem($item);
        }

        return $item;
    }

    public function __toString()
    {
        return $this->nameFr;
    }

    public function exportData($full = true, $locale = 'fr')
    {
        return $this->exportTimestampableData($full) + $this->exportSluggableData($full) + [
            'name' => $this->getName($locale),
            'slot' => $this->slot,
        ] + ($full ? [
            'effectArea' => $this->effectArea,
            'release' => $this->release,
            'preliminary' => $this->preliminary,
            'deprecated' => $this->deprecated,
        ] : []);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        return false;
    }
}
