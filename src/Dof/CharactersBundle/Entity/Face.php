<?php

namespace Dof\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\DataBundle\IdentifiableInterface;

use XN\DataBundle\LocalizedNameTrait;

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
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    use LocalizedNameTrait;

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

    public function __toString()
    {
        return $this->nameFr;
    }
}
