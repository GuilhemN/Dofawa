<?php
namespace Dof\ItemsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* Mount
*
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\MountRepository")
*/
class Mount extends Animal
{

    public function isMount() { return true; }
}
