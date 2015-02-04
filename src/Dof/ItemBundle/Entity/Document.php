<?php

namespace Dof\ItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use XN\Persistence\IdentifiableInterface;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\ItemBundle\ReleaseBoundTrait;

/**
 * Document
 *
 * @ORM\Table(name="dof_documents")
 * @ORM\Entity(repositoryClass="Dof\ItemBundle\Entity\DocumentRepository")
 */
class Document implements IdentifiableInterface, LocalizedNameInterface
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