<?php

namespace Dof\ArticlesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\DataBundle\IdentifiableInterface;
use XN\DataBundle\TimestampableInterface;
use XN\DataBundle\TimestampableTrait;
use XN\DataBundle\SluggableInterface;
use XN\DataBundle\SluggableTrait;
use XN\DataBundle\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

//Traduction Titre/Description
use XN\DataBundle\LocalizedNameTrait;
use XN\DataBundle\LocalizedDescriptionTrait;

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
     * @ORM\Column(name="keys", type="string", length=150)
     */
    private $keys;

    /**
     * @var integer
     *
     * @ORM\Column(name="validation", type="integer")
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
     * Set mail
     *
     * @param string $mail
     * @return Article
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
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
        return $this->getName();
    }
}
