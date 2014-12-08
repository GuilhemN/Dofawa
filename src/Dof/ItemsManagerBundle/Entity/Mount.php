<?php
namespace Dof\ItemsManagerBundle\Entity;

/**
* Mount
*
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\MountRepository")
*/
class Mount extends Animal
{

    public function isMount() { return true; }
}
