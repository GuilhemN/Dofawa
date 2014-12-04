<?php

namespace Dof\GraphicsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dof\GraphicsBundle\BasicPCLook;
use Dof\BuildBundle\Entity\Stuff;

/**
 * BuildLook
 *
 * @ORM\Table(name="dof_build_look")
 * @ORM\Entity(repositoryClass="Dof\GraphicsBundle\Entity\BuildLookRepository")
 */
class BuildLook extends BasicPCLook
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Dof\BuildBundle\Entity\Stuff", mappedBy="look")
     */
    private $stuff;


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
     * Set stuff
     *
     * @param Stuff $stuff
     * @return BuildLook
     */
    public function setStuff(Stuff $stuff)
    {
        $this->stuff = $stuff;

        return $this;
    }

    /**
     * Get stuff
     *
     * @return Stuff
     */
    public function getStuff()
    {
        return $this->stuff;
    }

    public function __clone() {
        if($this->id)
            $this->id = null;
    }
}
