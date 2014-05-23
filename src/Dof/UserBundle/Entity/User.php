<?php

namespace Dof\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="dof_user")
 * @ORM\Entity(repositoryClass="Dof\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="point", type="integer", nullable=true)
     */
    private $point=null;

    /**
     * @var string
     *
     * @ORM\Column(name="groupe", type="string", length=100, nullable=true)
     */
    private $groupe=null;

    /**
     * @var string
     *
     * @ORM\Column(name="guilde", type="string", length=100, nullable=true)
     */
    private $guilde=null;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=100, nullable=true)
     */
    private $lieu=null;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=120, nullable=true)
     */
    private $site=null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="born", type="date", nullable=true)
     */
    private $born;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inscription", type="date", nullable=true)
     */
    private $inscription;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbvisite", type="integer", nullable=true)
     */
    private $nbvisite=null;

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=100, nullable=true)
     */
    private $grade=null;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire=null;

    /**
     * @var string
     *
     * @ORM\Column(name="presentation", type="text", nullable=true)
     */
    private $presentation=null;

    /**
     * @var string
     *
     * @ORM\Column(name="horaires", type="string", length=100, nullable=true)
     */
    private $horaires=null;

    /**
     * @var string
     *
     * @ORM\Column(name="differentpseudo", type="string", length=100, nullable=true)
     */
    private $differentpseudo=null;


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
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set mdp
     *
     * @param string $mdp
     * @return User
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * Get mdp
     *
     * @return string 
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * Set point
     *
     * @param integer $point
     * @return User
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return integer 
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set groupe
     *
     * @param string $groupe
     * @return User
     */
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return string 
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return User
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
     * Set confirme
     *
     * @param boolean $confirme
     * @return User
     */
    public function setConfirme($confirme)
    {
        $this->confirme = $confirme;

        return $this;
    }

    /**
     * Get confirme
     *
     * @return boolean 
     */
    public function getConfirme()
    {
        return $this->confirme;
    }

    /**
     * Set guilde
     *
     * @param string $guilde
     * @return User
     */
    public function setGuilde($guilde)
    {
        $this->guilde = $guilde;

        return $this;
    }

    /**
     * Get guilde
     *
     * @return string 
     */
    public function getGuilde()
    {
        return $this->guilde;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     * @return User
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string 
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set site
     *
     * @param string $site
     * @return User
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string 
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set born
     *
     * @param \DateTime $born
     * @return User
     */
    public function setBorn($born)
    {
        $this->born = $born;

        return $this;
    }

    /**
     * Get born
     *
     * @return \DateTime 
     */
    public function getBorn()
    {
        return $this->born;
    }

    /**
     * Set inscription
     *
     * @param \DateTime $inscription
     * @return User
     */
    public function setInscription($inscription)
    {
        $this->inscription = $inscription;

        return $this;
    }

    /**
     * Get inscription
     *
     * @return \DateTime 
     */
    public function getInscription()
    {
        return $this->inscription;
    }

    /**
     * Set dernvisite
     *
     * @param string $dernvisite
     * @return User
     */
    public function setDernvisite($dernvisite)
    {
        $this->dernvisite = $dernvisite;

        return $this;
    }

    /**
     * Get dernvisite
     *
     * @return string 
     */
    public function getDernvisite()
    {
        return $this->dernvisite;
    }

    /**
     * Set nbvisite
     *
     * @param integer $nbvisite
     * @return User
     */
    public function setNbvisite($nbvisite)
    {
        $this->nbvisite = $nbvisite;

        return $this;
    }

    /**
     * Get nbvisite
     *
     * @return integer 
     */
    public function getNbvisite()
    {
        return $this->nbvisite;
    }

    /**
     * Set grade
     *
     * @param string $grade
     * @return User
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return User
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set presentation
     *
     * @param string $presentation
     * @return User
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return string 
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Set horaires
     *
     * @param string $horaires
     * @return User
     */
    public function setHoraires($horaires)
    {
        $this->horaires = $horaires;

        return $this;
    }

    /**
     * Get horaires
     *
     * @return string 
     */
    public function getHoraires()
    {
        return $this->horaires;
    }

    /**
     * Set differentpseudo
     *
     * @param string $differentpseudo
     * @return User
     */
    public function setDifferentpseudo($differentpseudo)
    {
        $this->differentpseudo = $differentpseudo;

        return $this;
    }

    /**
     * Get differentpseudo
     *
     * @return string 
     */
    public function getDifferentpseudo()
    {
        return $this->differentpseudo;
    }
}
