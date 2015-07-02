<?php

namespace XN\L10n;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait LocalizedNameTrait
{
    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="name_fr", type="string", length=150)
     */
    protected $name;

    /**
     * Sets name.
     *
     * @param string $name
     *
     * @return object
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
