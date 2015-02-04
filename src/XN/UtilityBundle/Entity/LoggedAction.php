<?php

namespace XN\UtilityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\OwnableInterface;
use Dof\Bundle\UserBundle\OwnableTrait; # TO CHANGE ACCORDING TO THE PROJECT
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SimpleLazyFieldTrait;

/**
 * LoggedAction
 *
 * @ORM\Table(name="xn_actions")
 * @ORM\Entity(repositoryClass="XN\UtilityBundle\Entity\LoggedActionRepository")
 */
class LoggedAction implements IdentifiableInterface, OwnableInterface, TimestampableInterface
{
    use OwnableTrait, TimestampableTrait, SimpleLazyFieldTrait;

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
     * @ORM\Column(name="_key", type="string", length=255)
     */
    private $key;

    /**
     * @var array
     *
     * @ORM\Column(name="context", type="json_array")
     */
    private $context;


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
     * Set key
     *
     * @param string $key
     * @return LoggedAction
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }


    /**
     * Set context
     *
     * @param array $context
     * @return LoggedAction
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }
}
