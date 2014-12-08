<?php
namespace Dof\ItemsManagerBundle\Entity;

/**
* Pet
*
* @ORM\Entity(repositoryClass="Dof\ItemsManagerBundle\Entity\PetRepository")
*/
class Pet extends Animal
{

    public function isPet() { return true; }
}
