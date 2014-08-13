<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

/**
 * ItemSetEffect
 *
 * @ORM\Table(name="dof_item_set_effects")
 * @ORM\Entity(repositoryClass="ItemSetEffectRepository")
 */
class ItemSetEffect implements IdentifiableInterface
{
    /**
     * @var integer
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
     * @var integer
     *
     * @ORM\Column(name="order_", type="integer")
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="param1", type="integer")
     */
    private $param1;

    /**
     * @var integer
     *
     * @ORM\Column(name="param2", type="integer")
     */
    private $param2;

    /**
     * @var integer
     *
     * @ORM\Column(name="param3", type="integer")
     */
    private $param3;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set combination
     *
     * @param ItemSetCombination $combination
     * @return ItemSetEffect
     */
    public function setCombination(ItemSetCombination $combination)
    {
        $this->combination = $combination;

        return $this;
    }

    /**
     * Get combination
     *
     * @return ItemSetCombination
     */
    public function getCombination()
    {
        return $this->combination;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return ItemTemplateEffect
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return ItemTemplateEffect
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set param1
     *
     * @param integer $param1
     * @return ItemTemplateEffect
     */
    public function setParam1($param1)
    {
        $this->param1 = $param1;

        return $this;
    }

    /**
     * Get param1
     *
     * @return integer
     */
    public function getParam1()
    {
        return $this->param1;
    }

    /**
     * Set param2
     *
     * @param integer $param2
     * @return ItemTemplateEffect
     */
    public function setParam2($param2)
    {
        $this->param2 = $param2;

        return $this;
    }

    /**
     * Get param2
     *
     * @return integer
     */
    public function getParam2()
    {
        return $this->param2;
    }

    /**
     * Set param3
     *
     * @param integer $param3
     * @return ItemTemplateEffect
     */
    public function setParam3($param3)
    {
        $this->param3 = $param3;

        return $this;
    }

    /**
     * Get param3
     *
     * @return integer
     */
    public function getParam3()
    {
        return $this->param3;
    }
}
