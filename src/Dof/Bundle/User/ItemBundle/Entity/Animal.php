<?php

namespace Dof\Bundle\User\ItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Animal.
 *
 * @ORM\Entity(repositoryClass="Dof\Bundle\User\ItemBundle\Entity\AnimalRepository")
 */
class Animal extends Item
{
    public function isAnimal()
    {
        return true;
    }
    public function getClassId()
    {
        return 'animal';
    }
}
