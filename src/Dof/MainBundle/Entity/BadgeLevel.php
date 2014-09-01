<?php

namespace Dof\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;

//Traduction Titre/Description
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;

use Dof\MainBundle\Entity\Badge;

/**
 * BadgeLevel
 *
 * @ORM\Table("dof_badge_levels")
 * @ORM\Entity(repositoryClass="Dof\MainBundle\Entity\BadgeLevelRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BadgeLevel implements TimestampableInterface
{
    use TimestampableTrait, LocalizedNameTrait, LocalizedDescriptionTrait;

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
     * @ORM\Column(name="minCount", type="integer")
     */
    private $minCount;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\MainBundle\Entity\Badge", inversedBy="levels")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=true)
     */
    protected $badge;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @Assert\Image(
     *     maxSize = "1024k",
     *     minWidth = 80,
     *     maxWidth = 80,
     *     minHeight = 80,
     *     maxHeight = 80,
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
     * Set minCount
     *
     * @param integer $minCount
     * @return BadgeLevel
     */
    public function setMinCount($minCount)
    {
        $this->minCount = $minCount;

        return $this;
    }

    /**
     * Get minCount
     *
     * @return integer
     */
    public function getMinCount()
    {
        return $this->minCount;
    }

    /**
     * Set badge
     *
     * @param Badge $badge
     * @return BadgeLevel
     */
    public function setBadge(Badge $badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Get badge
     *
     * @return Badge
     */
    public function getBadge()
    {
        return $this->badge;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/badges';
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

        if(!empty($this->path))
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
        $this->path = time().$this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @ORM\PreRemove
     */
    public function removeUpload(){
        @unlink($this->getAbsolutePath());
    }

    public function __toString(){
        return $this->getName();
    }
}
