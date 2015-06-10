<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use Dof\Bundle\CharacterBundle\EffectInterface;
use Dof\Bundle\CharacterBundle\EffectTrait;
use Dof\Common\GameTemplateString;

/**
 * ItemSetEffect.
 *
 * @ORM\Table(name="dof_item_set_effects")
 * @ORM\Entity(repositoryClass="ItemSetEffectRepository")
 */
class ItemSetEffect implements IdentifiableInterface, EffectInterface
{
    use EffectTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ItemSet
     *
     * @ORM\ManyToOne(targetEntity="ItemSetCombination", inversedBy="effects")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $combination;

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
     * Set combination.
     *
     * @param ItemSetCombination $combination
     *
     * @return ItemSetEffect
     */
    public function setCombination(ItemSetCombination $combination)
    {
        $this->combination = $combination;

        return $this;
    }

    /**
     * Get combination.
     *
     * @return ItemSetCombination
     */
    public function getCombination()
    {
        return $this->combination;
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

    public function getDescription($locale = 'fr', $full = false)
    {
        $desc = $this->getEffectTemplate()->expandDescription([
            '1' => $this->getParam1(),
            '2' => $this->getParam2(),
            '3' => $this->getParam3(),
        ], $locale);
        if ($full) {
            array_unshift($desc, ['['.$this->getEffectTemplate()->getId().'] ', GameTemplateString::COMES_FROM_TEMPLATE]);
        }

        return $desc;
    }

    public function __toString()
    {
        return $this->getPlainTextDescription();
    }
}
