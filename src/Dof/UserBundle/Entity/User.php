<?php

namespace Dof\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\MinorColumnsInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;
use XN\Metadata\OwnableInterface;
use XN\Security\TOTPAuthenticatableInterface;
use XN\Security\TOTPAuthenticatableTrait;
use Dof\UserBundle\OwnableTrait;

use XN\Metadata\FileTrait;
use XN\Metadata\FileInterface;

use FOS\MessageBundle\Model\ParticipantInterface;
use Dof\BuildBundle\Entity\PlayerCharacter;

/**
 * User
 *
 * @ORM\Table(name="dof_user")
 * @ORM\Entity(repositoryClass="Dof\UserBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser implements ParticipantInterface, IdentifiableInterface, TimestampableInterface, SluggableInterface, OwnableInterface, TOTPAuthenticatableInterface, MinorColumnsInterface, FileInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    use TimestampableTrait, SluggableTrait, OwnableTrait, TOTPAuthenticatableTrait, FileTrait;

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
     * @ORM\Column(name="guild", type="string", length=100, nullable=true)
     */
    private $guild = null;

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
    * @ORM\OneToMany(targetEntity="Dof\BuildBundle\Entity\PlayerCharacter", mappedBy="owner")
    * @ORM\JoinColumn(nullable=true)
    */
    private $builds;

    /**
    * @var array
    *
    * @ORM\Column(name="preferences", type="json_array", nullable=true)
    */
    private $preferences;

    /**
     * @var boolean
     *
     * @ORM\Column(name="petsManagerNotifications", type="boolean")
     */
    private $petsManagerNotifications;

    public function __construct()
    {
        parent::__construct();
        $this->builds = new ArrayCollection();
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
     * Set guild
     *
     * @param string $guild
     * @return User
     */
    public function setGuild($guild)
    {
        $this->guild = $guild;

        return $this;
    }

    /**
     * Get guild
     *
     * @return string
     */
    public function getGuild()
    {
        return $this->guild;
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

    /**
    * Add builds
    *
    * @param PlayerCharacter $builds
    * @return object
    */
    public function addBuild(PlayerCharacter $builds)
    {
        $this->builds[] = $builds;

        return $this;
    }

    /**
    * Remove builds
    *
    * @param PlayerCharacter $builds
    * @return object
    */
    public function removeBuild(PlayerCharacter $builds)
    {
        $this->builds->removeElement($builds);

        return $this;
    }

    /**
    * Get builds
    *
    * @return Collection
    */
    public function getBuilds()
    {
        return $this->builds;
    }

    public function getMinorColumns()
    {
        return array('lastLogin', 'totpLastTrialStamp', 'totpLastSuccessStamp', 'totpTrialCount', 'nbvisite');
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/avatars';
    }

    /**
     * Set petsManagerNotifications
     *
     * @param boolean $petsManagerNotifications
     * @return User
     */
    public function setPetsManagerNotifications($petsManagerNotifications)
    {
        $this->petsManagerNotifications = $petsManagerNotifications;

        return $this;
    }

    /**
     * Get petsManagerNotifications
     *
     * @return boolean
     */
    public function getPetsManagerNotifications()
    {
        return $this->petsManagerNotifications;
    }

    public function hasPreference($id) {
        return (bool) $this->preferences[$id];
    }

    public function addPreference($id, $value) {
        $this->preferences[$id] = $value;
        return $this;
    }

    /**
    * Set preferences
    *
    * @param array $preferences
    * @return User
    */
    public function setPreferences(array $preferences)
    {
        $this->preferences = $preferences;

        return $this;
    }

    /**
    * Get preferences
    *
    * @return array
    */
    public function getPreferences()
    {
        return $this->preferences;
    }
}
