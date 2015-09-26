<?php

namespace Dof\Bundle\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableTrait;
use XN\Metadata\OwnableInterface;
use XN\Security\TOTPAuthenticatableInterface;
use XN\Security\TOTPAuthenticatableTrait;
use Dof\Bundle\UserBundle\OwnableTrait;
use Dof\Bundle\User\CharacterBundle\Entity\PlayerCharacter;

/**
 * User.
 *
 * @ORM\Table(name="dof_user")
 * @ORM\Entity(repositoryClass="Dof\Bundle\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser implements IdentifiableInterface, OwnableInterface, TOTPAuthenticatableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    use TimestampableTrait, SluggableTrait, OwnableTrait, TOTPAuthenticatableTrait;

    protected $username;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="born", type="date", nullable=true)
     */
    private $born;

    /**
     * @ORM\OneToMany(targetEntity="Dof\Bundle\User\CharacterBundle\Entity\PlayerCharacter", mappedBy="owner")
     * @ORM\JoinColumn(nullable=true)
     */
    private $characters;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer")
     */
    protected $weight = 1;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"username"})
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    protected $slug;

    public function __construct()
    {
        parent::__construct();
        $this->characters = new ArrayCollection();
    }

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
     * Set born.
     *
     * @param \DateTime $born
     *
     * @return User
     */
    public function setBorn($born)
    {
        $this->born = $born;

        return $this;
    }

    /**
     * Get born.
     *
     * @return \DateTime
     */
    public function getBorn()
    {
        return $this->born;
    }

    /**
     * Add characters.
     *
     * @param PlayerCharacter $characters
     *
     * @return object
     */
    public function addCharacter(PlayerCharacter $characters)
    {
        $this->characters[] = $characters;

        return $this;
    }

    /**
     * Remove builds.
     *
     * @param PlayerCharacter $builds
     *
     * @return object
     */
    public function removeCharacter(PlayerCharacter $characters)
    {
        $this->characters->removeElement($characters);

        return $this;
    }

    /**
     * Get characters.
     *
     * @return Collection
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * Set weight.
     *
     * @param int $weight
     *
     * @return User
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight.
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    public function __toString()
    {
        return $this->getUsername();
    }
}
