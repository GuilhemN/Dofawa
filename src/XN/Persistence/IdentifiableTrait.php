<?php

namespace XN\Persistence;

trait IdentifiableTrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     */
    protected $id;

    use IdentifiableLightTrait;
}
