<?php

namespace XN\DataBundle;

interface IdentifierGeneratorInterface
{
	public function generate($num = 1);
}