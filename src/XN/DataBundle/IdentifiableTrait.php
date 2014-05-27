<?php

namespace XN\DataBundle;

trait IdentifiableTrait
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="bigint")
	 * @ORM\Id
	 */
	protected $id;
	
	use IdentifiableLightTrait;
}