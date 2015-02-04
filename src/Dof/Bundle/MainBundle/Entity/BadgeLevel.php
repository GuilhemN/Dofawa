<?php

namespace Dof\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;

//Traduction Titre/Description
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;
use XN\Metadata\FileTrait;
use XN\Metadata\FileInterface;

use Dof\Bundle\MainBundle\Entity\Badge;

/**
 * BadgeLevel
 *
 * @ORM\Table("dof_badge_levels")
 * @ORM\Entity(repositoryClass="Dof\Bundle\MainBundle\Entity\BadgeLevelRepository")
 */
class BadgeLevel implements IdentifiableInterface, TimestampableInterface, FileInterface
{
    use TimestampableTrait, LocalizedNameTrait, LocalizedDescriptionTrait, FileTrait;

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
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\MainBundle\Entity\Badge", inversedBy="levels")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=true)
     */
    protected $badge;

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

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/badges';
    }
}
