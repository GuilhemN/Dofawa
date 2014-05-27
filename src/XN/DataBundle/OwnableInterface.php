<?php

namespace XN\DataBundle;

interface OwnableInterface
{
	public function getCreator();
	public function setCreator($creator);

	public function getUpdater();
	public function setUpdater($updater);

	public function getOwner();
	public function setOwner($owner);
}