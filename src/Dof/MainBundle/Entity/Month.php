<?php

namespace Dof\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;

use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;

/**
 * Month
 *
 * @ORM\Table(name="dof_months")
 * @ORM\Entity(repositoryClass="Dof\MainBundle\Entity\MonthRepository")
 */
class Month implements IdentifiableInterface, SluggableInterface, LocalizedNameInterface
{
    use SluggableTrait, LocalizedNameTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;


    /**
    * Set id
    *
    * @param integer $id
    * @return Month
    */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString() {
        return $this->nameFr;
    }
}
