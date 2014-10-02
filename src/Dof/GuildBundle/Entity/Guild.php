<?php

namespace Dof\GuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

/**
 * Guild
 *
 * @ORM\Table(name="dof_guild")
 * @ORM\Entity(repositoryClass="Dof\GuildBundle\Entity\GuildRepository")
 */
class Guild implements IdentifiableInterface, TimestampableInterface, OwnableInterface
{
    use OwnableTrait, TimestampableTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="serveur", type="string", length=255)
     */
    private $serveur;

    /**
     * @var integer
     *
     * @ORM\Column(name="lvlguild", type="integer")
     */
    private $lvlguild;

    /**
     * @var integer
     *
     * @ORM\Column(name="lvlmini", type="integer")
     */
    private $lvlmini;

    /**
     * @var string
     *
     * @ORM\Column(name="leader", type="string", length=255)
     */
    private $leader;

    /**
     * @var boolean
     *
     * @ORM\Column(name="recruitment", type="boolean")
     */
    private $recruitment;

    /**
     * @var string
     *
     * @ORM\Column(name="speciality", type="string", length=255)
     */
    private $speciality;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="forum", type="string", length=255)
     */
    private $forum;


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
     * Set serveur
     *
     * @param string $serveur
     * @return Guild
     */
    public function setServeur($serveur)
    {
        $this->serveur = $serveur;

        return $this;
    }

    /**
     * Get serveur
     *
     * @return string 
     */
    public function getServeur()
    {
        return $this->serveur;
    }

    /**
     * Set lvlguild
     *
     * @param integer $lvlguild
     * @return Guild
     */
    public function setLvlguild($lvlguild)
    {
        $this->lvlguild = $lvlguild;

        return $this;
    }

    /**
     * Get lvlguild
     *
     * @return integer 
     */
    public function getLvlguild()
    {
        return $this->lvlguild;
    }

    /**
     * Set lvlmini
     *
     * @param integer $lvlmini
     * @return Guild
     */
    public function setLvlmini($lvlmini)
    {
        $this->lvlmini = $lvlmini;

        return $this;
    }

    /**
     * Get lvlmini
     *
     * @return integer 
     */
    public function getLvlmini()
    {
        return $this->lvlmini;
    }

    /**
     * Set leader
     *
     * @param string $leader
     * @return Guild
     */
    public function setLeader($leader)
    {
        $this->leader = $leader;

        return $this;
    }

    /**
     * Get leader
     *
     * @return string 
     */
    public function getLeader()
    {
        return $this->leader;
    }

    /**
     * Set recruitment
     *
     * @param boolean $recruitment
     * @return Guild
     */
    public function setRecruitment($recruitment)
    {
        $this->recruitment = $recruitment;

        return $this;
    }

    /**
     * Get recruitment
     *
     * @return boolean 
     */
    public function getRecruitment()
    {
        return $this->recruitment;
    }

    /**
     * Set speciality
     *
     * @param string $speciality
     * @return Guild
     */
    public function setSpeciality($speciality)
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * Get speciality
     *
     * @return string 
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }

    /**
     * Set description
     *
     * @param text $description
     * @return Guild
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set forum
     *
     * @param string $forum
     * @return Guild
     */
    public function setForum($forum)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     * Get forum
     *
     * @return string 
     */
    public function getForum()
    {
        return $this->forum;
    }
}
