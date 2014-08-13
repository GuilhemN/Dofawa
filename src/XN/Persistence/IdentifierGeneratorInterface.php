<?php

namespace XN\Persistence;

interface IdentifierGeneratorInterface
{
	public function generate($num = 1);
}
