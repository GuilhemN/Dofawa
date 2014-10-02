<?php

namespace Dof\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\MinorColumnsInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;
use XN\Metadata\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * User
 *
 * @ORM\Table(name="dof_user")
 * @ORM\Entity(repositoryClass="Dof\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser implements ParticipantInterface, IdentifiableInterface, TimestampableInterface, SluggableInterface, OwnableInterface, MinorColumnsInterface
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
     * @var integer
     *
     * @ORM\Column(name="point", type="integer", nullable=true)
     */
    private $point = null;

    /**
     * @var string
     *
     * @ORM\Column(name="groupe", type="string", length=100, nullable=true)
     */
    private $groupe = null;

    /**
     * @var string
     *
     * @ORM\Column(name="guilde", type="string", length=100, nullable=true)
     */
    private $guilde = null;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=100, nullable=true)
     */
    private $lieu = null;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=120, nullable=true)
     */
    private $site = null;

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
    private $nbvisite = null;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire = null;

    /**
     * @var string
     *
     * @ORM\Column(name="presentation", type="text", nullable=true)
     */
    private $presentation = null;

    /**
     * @var string
     *
     * @ORM\Column(name="horaires", type="string", length=100, nullable=true)
     */
    private $horaires = null;

    /**
     * @var string
     *
     * @ORM\Column(name="differentpseudo", type="string", length=100, nullable=true)
     */
    private $differentpseudo = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @Assert\Image(
     *     maxSize = "1024k",
     *     minWidth = 80,
     *     maxWidth = 150,
     *     minHeight = 80,
     *     maxHeight = 150,
     *     mimeTypesMessage = "Choisissez un fichier image valide.")
     */
    private $file;


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

    public function getAbsolutePath()
    {
        return null === $this->avatar
            ? null
            : $this->getUploadRootDir().'/'.$this->avatar;
    }

    public function getWebPath()
    {
        return null === $this->avatar
            ? 'bundles/dofuser/img/default.png'
            : $this->getUploadDir().'/'.$this->avatar;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return $this->getWebDir() . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/avatars';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        if(!empty($this->avatar))
            $this->removeUpload();

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            time().$this->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->avatar = time().$this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @ORM\PreRemove
     */
    public function removeUpload(){
        @unlink($this->getAbsolutePath());
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
