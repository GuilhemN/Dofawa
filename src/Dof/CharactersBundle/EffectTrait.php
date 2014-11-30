<?php
namespace Dof\CharactersBundle;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;

use Dof\CharactersBundle\Entity\EffectTemplate;

trait EffectTrait
{
    /**
     * @var EffectTemplate
     *
     * @ORM\ManyToOne(targetEntity="Dof\CharactersBundle\Entity\EffectTemplate")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $effectTemplate;

    /**
     * @var integer
     *
     * @ORM\Column(name="raw_param1", type="integer")
     */
    private $rawParam1;

    /**
     * @var object
     */
    private $param1;

    /**
     * @var integer
     *
     * @ORM\Column(name="raw_param2", type="integer")
     */
    private $rawParam2;

    /**
     * @var object
     */
    private $param2;

    /**
     * @var integer
     *
     * @ORM\Column(name="raw_param3", type="integer")
     */
    private $rawParam3;

    /**
     * @var object
     */
    private $param3;

    /**
     * @var Collection
     */
    private $fragments;

    /**
     * @var ContainerInterface
     */
    private $di;

    /**
     * Set effectTemplate
     *
     * @param EffectTemplate $effectTemplate
     * @return object
     */
    public function setEffectTemplate(EffectTemplate $effectTemplate)
    {
        $this->effectTemplate = $effectTemplate;

        return $this;
    }

    /**
     * Get effectTemplate
     *
     * @return EffectTemplate
     */
    public function getEffectTemplate()
    {
        return $this->effectTemplate;
    }

    /**
     * Set param1
     *
     * @param object|integer $param1
     * @return object
     */
    public function setParam1($param1)
    {
        $this->param1 = $param1;
        if ($param1 instanceof IdentifiableInterface)
            $this->rawParam1 = intval($param1->getId());
        else
            $this->rawParam1 = intval($param1);

        return $this;
    }

    /**
     * Get param1
     *
     * @return object|integer
     */
    public function getParam1()
    {
        return ($this->param1 !== null) ? $this->param1 : $this->rawParam1;
    }

    /**
     * Set param2
     *
     * @param object|integer $param2
     * @return object
     */
    public function setParam2($param2)
    {
        $this->param2 = $param2;
        if ($param2 instanceof IdentifiableInterface)
            $this->rawParam2 = intval($param2->getId());
        else
            $this->rawParam2 = intval($param2);

        return $this;
    }

    /**
     * Get param2
     *
     * @return object|integer
     */
    public function getParam2()
    {
        return ($this->param2 !== null) ? $this->param2 : $this->rawParam2;
    }

    /**
     * Set param3
     *
     * @param object|integer $param3
     * @return object
     */
    public function setParam3($param3)
    {
        $this->param3 = $param3;
        if ($param3 instanceof IdentifiableInterface)
            $this->rawParam3 = intval($param3->getId());
        else
            $this->rawParam3 = intval($param3);

        return $this;
    }

    /**
     * Get param3
     *
     * @return object|integer
     */
    public function getParam3()
    {
        return ($this->param3 !== null) ? $this->param3 : $this->rawParam3;
    }

    /**
     * Add fragments
     *
     * @param object $fragments
     * @return object
     */
    public function addFragment($fragments)
    {
        $frags = $this->getFragments();
        $frags[] = $fragments;

        return $this;
    }

    /**
     * Remove fragments
     *
     * @param object $fragments
     * @return object
     */
    public function removeFragment($fragments)
    {
        $this->getFragments()->removeElement($fragments);

        return $this;
    }

    /**
     * Get fragments
     *
     * @return Collection
     */
    public function getFragments()
    {
        if (!$this->fragments)
            $this->fragments = new ArrayCollection();
        return $this->fragments;
    }

    /**
     * Set container
     *
     * @param ContainerInterface $di
     * @return object
     */
    public function setContainer(ContainerInterface $di)
    {
        $this->di = $di;

        return $this;
    }

    /**
     * Get container
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->di;
    }
}
