<?php
namespace XN\BBCode;

interface TextFilterInterface
{
	public function matchAll(Text $source);
}
