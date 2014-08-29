<?php

namespace Dof\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\SluggableInterface;
use XN\Metadata\SluggableTrait;
use XN\Metadata\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

use XN\L10n\LocalizedOriginInterface;
use XN\L10n\LocalizedOriginTrait;

use Dof\ForumBundle\Entity\Forum;
use Dof\ForumBundle\Entity\Message;

/**
 * topic
 *
 * @ORM\Table(name="dof_forum_topics")
 * @ORM\Entity(repositoryClass="Dof\ForumBundle\Entity\TopicRepository")
 */
class Topic implements IdentifiableInterface, TimestampableInterface, SluggableInterface, OwnableInterface, LocalizedOriginInterface
{
    use TimestampableTrait, SluggableTrait, OwnableTrait, LocalizedOriginTrait;

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="locked", type="boolean")
     */
    private $locked;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\ForumBundle\Entity\Forum")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $forum;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Dof\ForumBundle\Entity\Message", mappedBy="topic")
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return topic
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean 
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set forum
     *
     * @param Forum $forum
     */
    public function setForum(Forum $forum)
    {
        $this->forum = $forum;
    }

     /**
     * Get forum
     *
     * @return Forum
     */
    public function getForum()
    {
        return $this->forum;
    }
    
    /**
     * Add messages
     *
     * @param Message $messages
     * @return Topic
     */
    public function addMessage(Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param Message $messages
     * @return Topic
     */
    public function removeMessage(Message $messages)
    {
        $this->messages->removeElement($messages);

        return $this;
    }

    /**
     * Get messages
     *
     * @return Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    public function __toString(){
        return $this->name;
    }
}
