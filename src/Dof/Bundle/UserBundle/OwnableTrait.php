<?php

namespace Dof\Bundle\UserBundle;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Dof\Bundle\UserBundle\Entity\User;

trait OwnableTrait
{
    /**
     * @var User
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $creator;

    /**
     * @var User
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $updater;

    /**
     * @var User
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Dof\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $owner;

    /**
     * Get creator.
     *
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set creator.
     *
     * @param User $creator
     *
     * @return OwnableInterface
     */
    public function setCreator($creator)
    {
        if ($creator instanceof User) {
            $this->creator = $creator;
        } else {
            $this->creator = null;
        }

        return $this;
    }

    /**
     * Get updater.
     *
     * @return User
     */
    public function getUpdater()
    {
        return $this->updater;
    }

    /**
     * Set updater.
     *
     * @param User $updater
     *
     * @return OwnableInterface
     */
    public function setUpdater($updater)
    {
        if ($updater instanceof User) {
            $this->updater = $updater;
        } else {
            $this->updater = null;
        }

        return $this;
    }

    /**
     * Get owner.
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set owner.
     *
     * @param User $owner
     *
     * @return OwnableInterface
     */
    public function setOwner($owner)
    {
        if ($owner instanceof User) {
            $this->owner = $owner;
        } else {
            $this->owner = null;
        }

        return $this;
    }

    protected function exportOwnableData($full = true)
    {
        return $full ? [
            'creator' => ($this->creator === null) ? null : $this->creator->exportData(false),
            'updater' => ($this->updater === null) ? null : $this->updater->exportData(false),
            'owner' => ($this->owner === null) ? null : $this->owner->exportData(false),
        ] : [];
    }
}
