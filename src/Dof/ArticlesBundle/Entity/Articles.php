<?php

namespace Dof\ArticlesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Articles
 *
 * @ORM\Table(name="dof_articles")
 * @ORM\Entity(repositoryClass="Dof\ArticlesBundle\Entity\ArticlesRepository")
 */
class Articles
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
     * @var integer
     *
     * @ORM\Column(name="iduser", type="integer")
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=150)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="tren", type="string", length=150)
     */
    private $trEn;

    /**
     * @var string
     *
     * @ORM\Column(name="tres", type="string", length=150)
     */
    private $trEs;

    /**
     * @var string
     *
     * @ORM\Column(name="trde", type="string", length=150)
     */
    private $trDe;

    /**
     * @var string
     *
     * @ORM\Column(name="txen", type="text", nullable=true)
     */
    private $txEn;

    /**
     * @var string
     *
     * @ORM\Column(name="txes", type="text", nullable=true)
     */
    private $txEs;

    /**
     * @var string
     *
     * @ORM\Column(name="txde", type="text", nullable=true)
     */
    private $txDe;

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
     * @var \DateTime
     *
     * @ORM\Column(name="inscription", type="datetime", nullable=true)
     */
    private $dernmodif;


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
     * Set idUser
     *
     * @param integer $idUser
     * @return Articles
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Articles
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Articles
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Articles
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set trEn
     *
     * @param string $trEn
     * @return Articles
     */
    public function setTrEn($trEn)
    {
        $this->trEn = $trEn;

        return $this;
    }

    /**
     * Get trEn
     *
     * @return string 
     */
    public function getTrEn()
    {
        return $this->trEn;
    }

    /**
     * Set trEs
     *
     * @param string $trEs
     * @return Articles
     */
    public function setTrEs($trEs)
    {
        $this->trEs = $trEs;

        return $this;
    }

    /**
     * Get trEs
     *
     * @return string 
     */
    public function getTrEs()
    {
        return $this->trEs;
    }

    /**
     * Set trDe
     *
     * @param string $trDe
     * @return Articles
     */
    public function setTrDe($trDe)
    {
        $this->trDe = $trDe;

        return $this;
    }

    /**
     * Get trDe
     *
     * @return string 
     */
    public function getTrDe()
    {
        return $this->trDe;
    }

    /**
     * Set txEn
     *
     * @param string $txEn
     * @return Articles
     */
    public function setTxEn($txEn)
    {
        $this->txEn = $txEn;

        return $this;
    }

    /**
     * Get txEn
     *
     * @return string 
     */
    public function getTxEn()
    {
        return $this->txEn;
    }

    /**
     * Set txEs
     *
     * @param string $txEs
     * @return Articles
     */
    public function setTxEs($txEs)
    {
        $this->txEs = $txEs;

        return $this;
    }

    /**
     * Get txEs
     *
     * @return string 
     */
    public function getTxEs()
    {
        return $this->txEs;
    }

    /**
     * Set txDe
     *
     * @param string $txDe
     * @return Articles
     */
    public function setTxDe($txDe)
    {
        $this->txDe = $txDe;

        return $this;
    }

    /**
     * Get txDe
     *
     * @return string 
     */
    public function getTxDe()
    {
        return $this->txDe;
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

    /**
     * Set dernmodif
     *
     * @param \DateTime $dernmodif
     * @return Articles
     */
    public function setDernmodif($dernmodif)
    {
        $this->dernmodif = $dernmodif;

        return $this;
    }

    /**
     * Get dernmodif
     *
     * @return \DateTime 
     */
    public function getDernmodif()
    {
        return $this->dernmodif;
    }
}
