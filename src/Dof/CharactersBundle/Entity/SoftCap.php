<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

/**
 * SoftCap
 *
 * @ORM\Table(name="dof_breed_soft_caps")
 * @ORM\Entity(repositoryClass="SoftCapRepository")
 */
class SoftCap implements IdentifiableInterface
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
     * @var Breed
     *
     * @ORM\ManyToOne(targetEntity="Breed", inversedBy="softCaps")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $breed;

    /**
     * @var integer
     *
     * @ORM\Column(name="characteristic", type="integer")
     */
    private $characteristic;

    /**
     * @var integer
     *
     * @ORM\Column(name="min", type="integer")
     */
    private $min;

    /**
     * @var integer
     *
     * @ORM\Column(name="cost", type="integer")
     */
    private $cost;

    /**
     * @var integer
     *
     * @ORM\Column(name="gain", type="integer")
     */
    private $gain;

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
     * Set breed
     *
     * @param Breed $breed
     * @return SoftCap
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
     * Set characteristic
     *
     * @param integer $characteristic
     * @return SoftCap
     */
    public function setCharacteristic($characteristic)
    {
        $this->characteristic = $characteristic;

        return $this;
    }

    /**
     * Get characteristic
     *
     * @return integer
     */
    public function getCharacteristic()
    {
        return $this->characteristic;
    }

    /**
     * Set min
     *
     * @param integer $min
     * @return SoftCap
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min
     *
     * @return integer
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set cost
     *
     * @param integer $cost
     * @return SoftCap
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return integer
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set gain
     *
     * @param integer $gain
     * @return SoftCap
     */
    public function setGain($gain)
    {
        $this->gain = $gain;

        return $this;
    }

    /**
     * Get gain
     *
     * @return integer
     */
    public function getGain()
    {
        return $this->gain;
    }
}
