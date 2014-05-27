<?php

namespace XN\DataBundle;

use \DateTime;

interface TimestampableInterface
{
	public function getCreatedAt();
	public function setCreatedAt(DateTime $createdAt);
	
	public function getUpdatedAt();
	public function setUpdatedAt(DateTime $updatedAt);
}