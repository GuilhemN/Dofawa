<?php

namespace XN\DataBundle;

interface SluggableInterface
{
	public function getSlug();
	public function setSlug($slug);
	
	public function __toString();
}