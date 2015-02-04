<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\Mapping as ORM;

/**
 * UseableItemTemplate
 *
 * @ORM\Entity(repositoryClass="Dof\Bundle\ItemBundle\Entity\UseableItemTemplateRepository")
 */
class UseableItemTemplate extends ItemTemplate
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="useable_on_self", type="boolean")
     */
    private $useableOnSelf;

    /**
     * @var boolean
     *
     * @ORM\Column(name="useable_on_others", type="boolean")
     */
    private $useableOnOthers;

    /**
     * @var boolean
     *
     * @ORM\Column(name="targetable", type="boolean")
     */
    private $targetable;

    /**
     * @var string
     *
     * @ORM\Column(name="target_criteria", type="string", length=127, nullable=true)
     */
    private $targetCriteria;

	public function __construct()
	{
		parent::__construct();
	}

    /**
     * Set useableOnSelf
     *
     * @param boolean $useableOnSelf
     * @return UseableItemTemplate
     */
    public function setUseableOnSelf($useableOnSelf)
    {
        $this->useableOnSelf = $useableOnSelf;

        return $this;
    }

    /**
     * Get useableOnSelf
     *
     * @return boolean
     */
    public function getUseableOnSelf()
    {
        return $this->useableOnSelf;
    }

    /**
     * Get useableOnSelf
     *
     * @return boolean
     */
    public function isUseableOnSelf()
    {
        return $this->useableOnSelf;
    }

    /**
     * Set useableOnOthers
     *
     * @param boolean $useableOnOthers
     * @return UseableItemTemplate
     */
    public function setUseableOnOthers($useableOnOthers)
    {
        $this->useableOnOthers = $useableOnOthers;

        return $this;
    }

    /**
     * Get useableOnOthers
     *
     * @return boolean
     */
    public function getUseableOnOthers()
    {
        return $this->useableOnOthers;
    }

    /**
     * Get useableOnOthers
     *
     * @return boolean
     */
    public function isUseableOnOthers()
    {
        return $this->useableOnOthers;
    }

    /**
     * Set targetable
     *
     * @param boolean $targetable
     * @return UseableItemTemplate
     */
    public function setTargetable($targetable)
    {
        $this->targetable = $targetable;

        return $this;
    }

    /**
     * Get targetable
     *
     * @return boolean
     */
    public function getTargetable()
    {
        return $this->targetable;
    }

    /**
     * Get targetable
     *
     * @return boolean
     */
    public function isTargetable()
    {
        return $this->targetable;
    }

    /**
     * Set targetCriteria
     *
     * @param string $targetCriteria
     * @return UseableItemTemplate
     */
    public function setTargetCriteria($targetCriteria)
    {
        $this->targetCriteria = $targetCriteria;

        return $this;
    }

    /**
     * Get targetCriteria
     *
     * @return string
     */
    public function getTargetCriteria()
    {
        return $this->targetCriteria;
    }

	public function isUseable() { return true; }
    public function getClassId() { return 'useable'; }

    public function exportData($full = true, $locale = 'fr')
    {
        return parent::exportData($full, $locale) + ($full ? [
            'useableOnSelf' => $this->useableOnSelf,
            'useableOnOthers' => $this->useableOnOthers,
            'targetable' => $this->targetable,
            'targetCriteria' => $this->targetCriteria
        ] : [ ]);
    }
    protected function importField($key, $value, ObjectManager $dm, $locale = 'fr')
    {
        if (parent::importField($key, $value, $dm, $locale))
            return true;
        return false;
    }
}
