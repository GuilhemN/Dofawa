<?php

namespace Dof\Bundle\CharacterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;

/**
 * SoftCap.
 *
 * @ORM\Table(name="dof_breed_soft_caps")
 * @ORM\Entity(repositoryClass="SoftCapRepository")
 */
class SoftCap implements IdentifiableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Breed
     *
     * @ORM\ManyToOne(targetEntity="Breed", inversedBy="softCaps")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $breed;

    /**
     * @var int
     *
     * @ORM\Column(name="characteristic", type="integer")
     */
    private $characteristic;

    /**
     * @var int
     *
     * @ORM\Column(name="min", type="integer")
     */
    private $min;

    /**
     * @var int
     *
     * @ORM\Column(name="cost", type="integer")
     */
    private $cost;

    /**
     * @var int
     *
     * @ORM\Column(name="gain", type="integer")
     */
    private $gain;

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
     * Set breed.
     *
     * @param Breed $breed
     *
     * @return SoftCap
     */
    public function setBreed(Breed $breed)
    {
        $this->breed = $breed;

        return $this;
    }

    /**
     * Get breed.
     *
     * @return Breed
     */
    public function getBreed()
    {
        return $this->breed;
    }

    /**
     * Set characteristic.
     *
     * @param int $characteristic
     *
     * @return SoftCap
     */
    public function setCharacteristic($characteristic)
    {
        $this->characteristic = $characteristic;

        return $this;
    }

    /**
     * Get characteristic.
     *
     * @return int
     */
    public function getCharacteristic()
    {
        return $this->characteristic;
    }

    /**
     * Set min.
     *
     * @param int $min
     *
     * @return SoftCap
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min.
     *
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set cost.
     *
     * @param int $cost
     *
     * @return SoftCap
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost.
     *
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set gain.
     *
     * @param int $gain
     *
     * @return SoftCap
     */
    public function setGain($gain)
    {
        $this->gain = $gain;

        return $this;
    }

    /**
     * Get gain.
     *
     * @return int
     */
    public function getGain()
    {
        return $this->gain;
    }
}
