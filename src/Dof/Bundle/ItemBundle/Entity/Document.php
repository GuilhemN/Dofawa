<?php

namespace Dof\Bundle\ItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use XN\Persistence\IdentifiableInterface;
use XN\L10n\LocalizedNameInterface;
use XN\L10n\LocalizedNameTrait;
use Dof\Bundle\ItemBundle\ReleaseBoundTrait;

/**
 * Document.
 *
 * @ORM\Table(name="dof_documents")
 * @ORM\Entity(repositoryClass="Dof\Bundle\ItemBundle\Entity\DocumentRepository")
 */
class Document implements IdentifiableInterface, LocalizedNameInterface
{
    use LocalizedNameTrait, ReleaseBoundTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return ItemTemplate
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
