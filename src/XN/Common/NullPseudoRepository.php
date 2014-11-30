<?php
namespace XN\Common;

class NullPseudoRepository
{
	public function find()
	{
		return null;
	}
	public function findOneBy()
	{
		return null;
	}
	public function findBy()
	{
		return [ ];
	}
	public function findAll()
	{
		return [ ];
	}
}
