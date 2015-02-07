<?php

namespace Dof\Bundle\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;
use XN\Metadata\OwnableInterface;
use Dof\Bundle\UserBundle\OwnableTrait;

//Traduction Titre/Description
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;

/**
 * Article
 *
 * @ORM\Table(name="dof_articles")
 * @ORM\Entity(repositoryClass="Dof\Bundle\CMSBundle\Entity\ArticleRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({"article" = "Article", "dungeon" = "DungeonArticle", "quest" = "QuestArticle", "tutorial" = "TutorialArticle", "collection" = "CollectionArticle"})
 */
class Article implements IdentifiableInterface, TimestampableInterface, SluggableInterface, OwnableInterface, LocalizedNameInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    use TimestampableTrait, SluggableTrait, OwnableTrait, LocalizedNameTrait, LocalizedDescriptionTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="keys_", type="string", length=150)
     */
    protected $keys;

    /**
     * @var integer
     *
     * @ORM\Column(name="validation", type="boolean")
     */
    protected $published;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=150)
     */
    protected $category;

    /**
    * @ORM\ManyToMany(targetEntity="Dof\Bundle\CMSBundle\Entity\CollectionArticle", inversedBy="children")
    */
    protected $parents;

    public function __construct()
    {
        $this->parents = new ArrayCollection();
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
     * Set keys
     *
     * @param string $keys
     * @return Article
     */
    public function setKeys($keys)
    {
        $this->keys = $keys;

        return $this;
    }

    /**
     * Get keys
     *
     * @return string
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * Set published
     *
     * @param integer $published
     * @return Article
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return integer
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Article
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
    * Add parents
    *
    * @param CollectionArticle $parents
    * @return Article
    */
    public function addParent(CollectionArticle $parents)
    {
        $this->parents[] = $parents;

        return $this;
    }

    /**
    * Remove parents
    *
    * @param CollectionArticle $parents
    * @return Article
    */
    public function removeParent(CollectionArticle $parents)
    {
        $this->parents->removeElement($parents);

        return $this;
    }

    /**
    * Get parents
    *
    * @return Collection
    */
    public function getParents()
    {
        return $this->parents;
    }

    public function __toString()
    {
        return $this->nameFr;
    }

    public function isCollection() { return false; }
    public function isQuestArticle() { return false; }
    public function isDungeonArticle() { return false; }
    public function isTutorialArticle() { return false; }

    public function getClass() { return 'article'; }
}
