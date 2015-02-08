<?php
namespace XN\BBCode\TextFilter;

use XN\BBCode\TagTemplateInterface;

class NewLineTextFilter extends RegexTextFilter
{
	private $template;

	public function __construct(TagTemplateInterface $template)
	{
		$this->template = $template;
	}

	public function getRegex()
	{
		return '#\r\n?|\n\r?#';
	}

	protected function hydrate(array $match, $offset)
	{
		return new Tag($this->template, $match[0][0], $offset);
	}
}
