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

//Traduction Titre/Description
use XN\L10n\LocalizedNameTrait;
use XN\L10n\LocalizedDescriptionTrait;

use Dof\ForumBundle\Entity\Category;
use Dof\ForumBundle\Entity\Topic;

/**
 * Forum
 *
 * @ORM\Table(name="dof_forum_forums")
 * @ORM\Entity(repositoryClass="Dof\ForumBundle\Entity\ForumRepository")
 */
class Forum implements IdentifiableInterface, TimestampableInterface, SluggableInterface
{
    use TimestampableTrait, SluggableTrait, LocalizedNameTrait, LocalizedDescriptionTrait;

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
     * @ORM\Column(name="_index", type="integer")
     */
    private $index;

    /**
     * @ORM\ManyToOne(targetEntity="Dof\ForumBundle\Entity\Category", inversedBy="forums")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $category;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Dof\ForumBundle\Entity\Topic", mappedBy="forum")
     */
    private $topics;

    public function __construct()
    {
        $this->topics = new ArrayCollection();
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
     * Set index
     *
     * @param integer $index
     * @return Forum
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index
     *
     * @return integer
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set category
     *
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

     /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add topics
     *
     * @param Topic $topics
     * @return Forum
     */
    public function addTopic(Topic $topics)
    {
        $this->topics[] = $topics;

        return $this;
    }

    /**
     * Remove topics
     *
     * @param Topic $topics
     * @return Forum
     */
    public function removeTopic(Topic $topics)
    {
        $this->topics->removeElement($topics);

        return $this;
    }

    /**
     * Get topics
     *
     * @return Collection
     */
    public function getTopics()
    {
        return $this->topics;
    }

    public function __toString(){
        return $this->getName();
    }
}
