<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

use XN\L10n\LocalizedNameTrait;

/**
 * Face
 *
 * @ORM\Table(name="dof_breed_faces")
 * @ORM\Entity(repositoryClass="FaceRepository")
 */
class Face implements IdentifiableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string")
     */
    private $label;

    /**
     * @var Breed
     *
     * @ORM\ManyToOne(targetEntity="Breed", inversedBy="faces")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $breed;

    /**
     * @var integer
     *
     * @ORM\Column(name="gender", type="integer")
     */
    private $gender;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_", type="integer")
     */
    private $order;

    /**
     * Set id
     *
     * @param integer $id
     * @return Face
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

    /**
     * Set label
     *
     * @param string $label
     * @return Face
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set breed
     *
     * @param Breed $breed
     * @return Face
     */
    public function setBreed(Breed $breed)
    {
        $this->breed = $breed;

        return $this;
    }

    /**
     * Get breed
     *
     * @return Breed
     */
    public function getBreed()
    {
        return $this->breed;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     * @return Face
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return integer
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return Face
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

    public function __toString()
    {
        return $this->label;
    }
}
