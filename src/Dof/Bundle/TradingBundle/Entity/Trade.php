<?php

namespace Dof\Bundle\TradingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use Dof\Bundle\UserBundle\OwnableTrait;
use Dof\Bundle\ItemBundle\Entity\ItemTemplate;
use Dof\Bundle\MainBundle\Entity\Server;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\MainBundle\Entity\Server")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $server;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     * @Groups({"price"})
     */
    protected $price;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer")
     */
    protected $weight = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="valid", type="boolean")
     */
    protected $valid = false;

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
     * Set server.
     *
     * @param Server $server
     *
     * @return Trade
     */
    public function setServer(Server $server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server.
     *
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
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
    /**
     * Set valid
     *
     * @param boolean $valid
     * @return Trade
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid
     *
     * @return boolean
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Get valid
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * @Groups({"price"})
     */
    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }
}
