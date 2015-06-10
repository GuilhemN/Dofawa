<?php

namespace Dof\Bundle\Social\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;
use Dof\Bundle\UserBundle\OwnableTrait;
//Traduction Titre/Description
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;

/**
 * Category.
 *
 * @ORM\Table(name="dof_forum_category")
 * @ORM\Entity(repositoryClass="Dof\Bundle\Social\ForumBundle\Entity\CategoryRepository")
 */
class Category implements IdentifiableInterface, TimestampableInterface, SluggableInterface, LocalizedNameInterface
{
    use TimestampableTrait, SluggableTrait, OwnableTrait, LocalizedNameTrait, LocalizedDescriptionTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="_index", type="integer")
     */
    private $index;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Dof\Bundle\Social\ForumBundle\Entity\Forum", mappedBy="category")
     * @ORM\OrderBy({ "index" = "ASC" })
     */
    private $forums;

    public function __construct()
    {
        $this->forums = new ArrayCollection();
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
     * Set index.
     *
     * @param int $index
     *
     * @return Category
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index.
     *
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Add forums.
     *
     * @param Forum $forums
     *
     * @return Category
     */
    public function addForum(Forum $forums)
    {
        $this->forums[] = $forums;

        return $this;
    }

    /**
     * Remove forums.
     *
     * @param Forum $forums
     *
     * @return Category
     */
    public function removeForum(Forum $forums)
    {
        $this->forums->removeElement($forums);

        return $this;
    }

    /**
     * Get forums.
     *
     * @return Collection
     */
    public function getForums()
    {
        return $this->forums;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
