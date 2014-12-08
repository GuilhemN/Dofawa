<?php
namespace Dof\ItemsManagerBundle\Entity;

/**
* Animal
*
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\AnimalRepository")
*/
class Animal extends Item
{

    public function isAnimal() { return true; }
}
