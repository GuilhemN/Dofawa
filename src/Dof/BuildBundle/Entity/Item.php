<?php

namespace Dof\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\ItemsBundle\CharacteristicsRangeTrait;
use XN\DataBundle\IdentifiableInterface;
use XN\DataBundle\TimestampableInterface;
use XN\DataBundle\TimestampableTrait;

use Dof\BuildBundle\Stuff;

/**
 * Item
 *
 * @ORM\Table(name="dof_build_item")
 * @ORM\Entity(repositoryClass="Dof\BuildBundle\Entity\ItemRepository")
 */
class Item implements IdentifiableInterface, TimestampableInterface
{
    use CharacteristicsRangeTrait, TimestampableTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Stuff
     *
     * @ORM\ManyToOne(targetEntity="Dof\BuildBundle\Entity\Stuff", inversedBy="items")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $stuff;


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
     * Set stuff
     *
     * @param Stuff $stuff
     * @return Item
     */
    public function setStuff(Stuff $stuff)
    {
        $this->stuff = $stuff;

        return $this;
    }

    /**
     * Get stuff
     *
     * @return Stuff
     */
    public function getStuff()
    {
        return $this->stuff;
    }
}
