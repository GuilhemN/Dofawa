<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use Dof\ItemsBundle\PrimaryBonusInterface;
use Dof\ItemsBundle\PrimaryBonusTrait;

use Dof\ItemsBundle\CharacteristicsTrait;

/**
 * ItemSetCombination
 *
 * @ORM\Table(name="dof_item_set_combinations")
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\ItemSetCombinationRepository")
 */
class ItemSetCombination implements IdentifiableInterface, PrimaryBonusInterface
{
    use PrimaryBonusTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ItemSet
     *
     * @ORM\ManyToOne(targetEntity="ItemSet", inversedBy="combinations")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $set;

    /**
     * @var integer
     *
     * @ORM\Column(name="item_count", type="integer")
     */
    private $itemCount;

    use CharacteristicsTrait;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ItemSetEffect", mappedBy="combination")
     * @ORM\OrderBy({ "order" = "ASC", "id" = "ASC" })
     */
    private $effects;

    public function __construct()
    {
        $this->effects = new ArrayCollection();
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
     * Set set
     *
     * @param ItemSet $set
     * @return ItemSetCombination
     */
    public function setSet(ItemSet $set)
    {
        $this->set = $set;

        return $this;
    }

    /**
     * Get set
     *
     * @return ItemSet
     */
    public function getSet()
    {
        return $this->set;
    }

    /**
     * Set itemCount
     *
     * @param integer $itemCount
     * @return ItemSetCombination
     */
    public function setItemCount($itemCount)
    {
        $this->itemCount = $itemCount;

        return $this;
    }

    /**
     * Get itemCount
     *
     * @return integer
     */
    public function getItemCount()
    {
        return $this->itemCount;
    }

    /**
     * Add effects
     *
     * @param ItemSetEffect $effects
     * @return ItemSetCombination
     */
    public function addEffect(ItemSetEffect $effects)
    {
        $this->effects[] = $effects;

        return $this;
    }

    /**
     * Remove effects
     *
     * @param ItemSetEffect $effects
     * @return ItemSetCombination
     */
    public function removeEffect(ItemSetEffect $effects)
    {
        $this->effects->removeElement($effects);

        return $this; 
    }

    /**
     * Get effects
     *
     * @return Collection
     */
    public function getEffects()
    {
        return $this->effects;
    }

    public function getCharacteristicsForPrimaryBonus(array $primaryFields, array $caracts = array()){
        foreach($primaryFields as $k => $v){
			if(!isset($caracts[$v['primaryBonus']]))
				$caracts[$v['primaryBonus']] = 0;

            $caracts[$v['primaryBonus']] += $this->{'get' . ucfirst($k)}() * $v['weight'];
        }

        return $caracts;
    }

    public function getCascadeForPrimaryBonus(){
        return $this->set;
    }
}
