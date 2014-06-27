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
 * Articles
 *
 * @ORM\Table(name="dof_articles")
 * @ORM\Entity(repositoryClass="Dof\ArticlesBundle\Entity\ArticlesRepository")
 */
class Articles implements IdentifiableInterface, TimestampableInterface, SluggableInterface, OwnableInterface
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
     * @ORM\Column(name="cles", type="string", length=150)
     */
    private $cles;

    /**
     * @var integer
     *
     * @ORM\Column(name="validation", type="integer")
     */
    private $validation;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=150)
     */
    private $categorie;

    /**
     * @var integer
     *
     * @ORM\Column(name="news", type="integer")
     */
    private $news;

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
     * Set cles
     *
     * @param string $cles
     * @return Articles
     */
    public function setCles($cles)
    {
        $this->cles = $cles;

        return $this;
    }

    /**
     * Get cles
     *
     * @return string
     */
    public function getCles()
    {
        return $this->cles;
    }

    /**
     * Set validation
     *
     * @param integer $validation
     * @return Articles
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * Get validation
     *
     * @return integer
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Articles
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
     * Set categorie
     *
     * @param string $categorie
     * @return Articles
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set news
     *
     * @param integer $news
     * @return Articles
     */
    public function setNews($news)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * Get news
     *
     * @return integer
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set journal
     *
     * @param string $journal
     * @return Articles
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
        return $this->titre;
    }
}
