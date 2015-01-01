<?php

namespace Dof\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\Metadata\TimestampableInterface;
use XN\Metadata\TimestampableTrait;
use XN\Metadata\OwnableInterface;
use Dof\UserBundle\OwnableTrait;

use XN\L10n\LocalizedOriginInterface;
use XN\L10n\LocalizedOriginTrait;

/**
 * Proposition
 *
 * @ORM\Table(name="dof_article_propositions")
 * @ORM\Entity(repositoryClass="Dof\CMSBundle\Entity\PropositionRepository")
 */
class Proposition implements IdentifiableInterface, TimestampableInterface, OwnableInterface, LocalizedOriginInterface
{
    use TimestampableTrait, OwnableTrait, LocalizedOriginTrait;

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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\Column(name="options", type="json_array")
     */
    private $options;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Dof\CMSBundle\Entity\Article")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $article;


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
     * @return Proposition
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
     * Set description
     *
     * @param string $description
     * @return Proposition
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set options
     *
     * @param array $options
     * @return Proposition
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
    * Set article
    *
    * @param Article $article
    * @return Proposition
    */
    public function setArticle(Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
    * Get article
    *
    * @return Article
    */
    public function getArticle()
    {
        return $this->article;
    }
}
