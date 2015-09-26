<?php

namespace Dof\Bundle\TradingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use Dof\Bundle\UserBundle\OwnableTrait;
use Dof\Bundle\ItemBundle\Entity\ItemTemplate;

/**
 * Trade.
 *
 * @ORM\Table(name="dof_trades")
 * @ORM\Entity(repositoryClass="Dof\Bundle\TradingBundle\Entity\TradeRepository")
 */
class Trade implements IdentifiableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    use OwnableTrait, TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\ItemBundle\Entity\ItemTemplate")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $item;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    protected $price;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer")
     */
    protected $weight = 0;

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
     * @return Trade
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
     * Set price.
     *
     * @param int $price
     *
     * @return Trade
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set weight.
     *
     * @param bool $weight
     *
     * @return Trade
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight.
     *
     * @return bool
     */
    public function getWeight()
    {
        return $this->weight;
    }
}
