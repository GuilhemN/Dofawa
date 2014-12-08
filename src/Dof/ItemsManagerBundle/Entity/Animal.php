<?php
namespace Dof\ItemsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* Animal
*
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\AnimalRepository")
*/
class Animal extends Item
{

    public function isAnimal() { return true; }
    public function getClassId() { return 'animal'; }
}
