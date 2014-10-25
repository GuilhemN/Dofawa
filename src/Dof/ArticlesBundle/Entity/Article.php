<?php

namespace Dof\ArticlesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;
use XN\Metadata\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

//Traduction Titre/Description
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;

/**
 * Article
 *
 * @ORM\Table(name="dof_articles")
 * @ORM\Entity(repositoryClass="Dof\ArticlesBundle\Entity\ArticleRepository")
 */
class Article implements IdentifiableInterface, TimestampableInterface, SluggableInterface, OwnableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    use TimestampableTrait, SluggableTrait, OwnableTrait, LocalizedNameTrait, LocalizedDescriptionTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="keys_", type="string", length=150)
     */
    private $keys;

    /**
     * @var integer
     *
     * @ORM\Column(name="validation", type="boolean")
     */
    private $published;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=150)
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="journal", type="text", nullable=true)
     */
    private $journal;

    /**
     * @ORM\OneToMany(targetEntity="Dof\ArticlesBundle\Entity\Article", mappedBy="originalArticle")
     * @ORM\JoinColumn(nullable=true)
     */
    private $edits;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\ArticlesBundle\Entity\Article", inversedBy="edits")
     * @ORM\JoinColumn(nullable=true)
     */
    private $originalArticle;

    /**
     * @var integer
     *
     * @ORM\Column(name="archive", type="boolean")
     */
    private $archive;

    public function __construct()
    {
        $this->edits = new ArrayCollection();
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
     * Set news
     *
     * @param integer $news
     * @return Article
     */
    public function setType($type)
    {
        $this->news = $type;

        return $this;
    }

    /**
     * Get news
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set journal
     *
     * @param string $journal
     * @return Article
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journal
     *
     * @return string
     */
    public function getJournal()
    {
        return $this->journal;
    }

    public function __toString()
    {
        return $this->getNameFr();
    }
    
    /**
     * Add edits
     *
     * @param Article $edits
     * @return object
     */
    public function addEdit(Article $edits)
    {
        $this->edits[] = $edits;

        return $this;
    }

    /**
     * Remove edits
     *
     * @param Article $edits
     * @return object
     */
    public function removeEdit(Article $edits)
    {
        $this->edits->removeElement($edits);

        return $this;
    }

    /**
     * Get edits
     *
     * @return Collection
     */
    public function getEdits()
    {
        return $this->edits;
    }

    /**
     * Set originalArticle
     *
     * @param string $originalArticle
     * @return Article
     */
    public function setOriginalArticle($originalArticle)
    {
        $this->originalArticle = $originalArticle;

        return $this;
    }

    /**
     * Get originalArticle
     *
     * @return string
     */
    public function getOriginalArticle()
    {
        return $this->originalArticle;
    }

    /**
     * Set archive
     *
     * @param integer $archive
     * @return Article
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive
     *
     * @return integer
     */
    public function getArchive()
    {
        return $this->archive;
    }
}
