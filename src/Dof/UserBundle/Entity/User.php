<?php

namespace Dof\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

use XN\DataBundle\IdentifiableInterface;
use XN\DataBundle\MinorColumnsInterface;
use XN\DataBundle\TimestampableInterface;
use XN\DataBundle\TimestampableTrait;
use XN\DataBundle\SluggableInterface;
use XN\DataBundle\SluggableTrait;
use XN\DataBundle\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

/**
 * User
 *
 * @ORM\Table(name="dof_user")
 * @ORM\Entity(repositoryClass="Dof\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser implements IdentifiableInterface, TimestampableInterface, SluggableInterface, OwnableInterface, MinorColumnsInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    use TimestampableTrait, SluggableTrait, OwnableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=150)
     */
    private $nom;

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
    
    public function getMinorColumns()
    {
        return array('lastLogin', 'nbvisite');
    }

    public function __toString()
    {
        return $this->getUsername();
    }
}
