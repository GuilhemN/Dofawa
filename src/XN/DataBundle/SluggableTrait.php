<?php

namespace XN\DataBundle;

use Doctrine\ORM\Mapping as ORM;

trait SluggableTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
	protected $slug;

	/**
	 * Get slug
	 *
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * Set slug
	 *
	 * @param string $slug
	 * @return SluggableInterface
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;
		return $this;
	}
	
	/**
	 * Get a string representation of the entity
	 *
	 * @return string
	 */
	public abstract function __toString();
	
	protected function exportSluggableData($full = true)
	{
		return [
			'slug' => $this->slug
		];
	}
}