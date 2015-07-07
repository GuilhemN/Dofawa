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
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * ItemSet.
 *
 * @ORM\Table(name="dof_item_sets")
 * @ORM\Entity(repositoryClass="ItemSetRepository")
 */
class ItemSet implements IdentifiableInterface, ExportableInterface, LocalizedNameInterface
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
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="EquipmentTemplate", mappedBy="set")
     * @ORM\OrderBy({ "level" = "DESC" })
     */
    private $items;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemSetCombination", mappedBy="set")
     * @ORM\OrderBy({ "itemCount" = "ASC", "id" = "ASC" })
     */
    private $combinations;

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
        $this->combinations = new ArrayCollection();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return ItemSet
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
     * Add items.
     *
     * @param EquipmentTemplate $items
     *
     * @return ItemSet
     */
    public function addItem(EquipmentTemplate $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items.
     *
     * @param EquipmentTemplate $items
     *
     * @return ItemSet
     */
    public function removeItem(EquipmentTemplate $items)
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

    /**
     * Add combinations.
     *
     * @param ItemSetCombination $combinations
     *
     * @return ItemSet
     */
    public function addCombination(ItemSetCombination $combinations)
    {
        $this->combinations[] = $combinations;

        return $this;
    }

    /**
     * Remove combinations.
     *
     * @param ItemSetCombination $combinations
     *
     * @return ItemSet
     */
    public function removeCombination(ItemSetCombination $combinations)
    {
        $this->combinations->removeElement($combinations);

        return $this;
    }

    /**
     * Get combinations.
     *
     * @return Collection
     */
    public function getCombinations()
    {
        return $this->combinations;
    }

    /**
     * Get level.
     *
     * @return int
     */
    public function getLevel()
    {
        $max = 0;
        foreach ($this->items as $i) {
            $max = max($i->getLevel(), $max);
        }

        return $max;
    }

    /**
     * Set itemCount.
     *
     * @param int $itemCount
     *
     * @return ItemSet
     */
    public function setItemCount($itemCount)
    {
        $this->itemCount = $itemCount;

        return $this;
    }

    /**
     * Get itemCount.
     *
     * @return int
     */
    public function getItemCount()
    {
        return $this->itemCount;
    }

    public function __toString()
    {
        return $this->nameFr;
    }

    public function exportData($full = true, $locale = 'fr')
    {
        return $this->exportTimestampableData($full) + $this->exportSluggableData($full) + [
            'name' => $this->getName($locale),
        ] + ($full ? [
            'items' => array_map(function ($ent) use ($locale) { return $ent->exportData(false, $locale); }, $this->items->toArray()),
            'release' => $this->release,
            'preliminary' => $this->preliminary,
            'deprecated' => $this->deprecated,
        ] : []);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        return false;
    }

    public function getCharacteristicsForPrimaryBonus(array $primaryFields, array $caracts = array())
    {
        $biggestCombination = null;
        $countItem = count($this->getItems());
        foreach ($this->getCombinations() as $combination) {
            if ($combination->getItemCount() == $countItem) {
                $biggestCombination = $combination;

                $caracts = $biggestCombination->getCharacteristicsForPrimaryBonus($primaryFields, $caracts);
                break;
            }
        }

        foreach ($this->getItems() as $item) {
            $caracts = $item->getCharacteristicsForPrimaryBonus($primaryFields, $caracts);
        }

        return $caracts;
    }
}
