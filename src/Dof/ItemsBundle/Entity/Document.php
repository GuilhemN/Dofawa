<?php

namespace Dof\ItemsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\ItemsBundle\ReleaseBoundTrait;

/**
 * Document
 *
 * @ORM\Table(name="dof_documents")
 * @ORM\Entity(repositoryClass="Dof\ItemsBundle\Entity\DocumentRepository")
 */
class Document implements LocalizedNameInterface
{
    use LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
    * Set id
    *
    * @param integer $id
    * @return ItemTemplate
    */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
}
