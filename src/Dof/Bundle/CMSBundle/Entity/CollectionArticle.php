<?php

namespace Dof\Bundle\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * CollectionArticle.
 *
 * @ORM\Entity(repositoryClass="Dof\Bundle\CMSBundle\Entity\CollectionArticleRepository")
 */
class CollectionArticle extends Article
{
    /**
     * @ORM\ManyToMany(targetEntity="Dof\Bundle\CMSBundle\Entity\Article", mappedBy="parents")
     */
    private $children;

    public function __construct()
    {
        parent::__construct();
        $this->children = new ArrayCollection();
    }

    /**
     * Add children.
     *
     * @param Article $children
     *
     * @return CollectionArticle
     */
    public function addChild(Article $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children.
     *
     * @param Article $children
     *
     * @return CollectionArticle
     */
    public function removeChild(Article $children)
    {
        $this->children->removeElement($children);

        return $this;
    }

    /**
     * Get children.
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function isCollection()
    {
        return true;
    }
    public function getClass()
    {
        return 'collection';
    }
}
