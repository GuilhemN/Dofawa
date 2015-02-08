<?php
namespace XN\BBCode;

class Language
{
	private $tags;
	private $filters;
	private $lenient;

	private $parser;

	public function __construct()
	{
		$this->tags = [ ];
		$this->filters = [ ];
		$this->lenient = false;
		$this->parser = null;
	}

	public function registerTagTemplate(TagTemplateInterface $tag)
	{
		foreach ($tag->getNames() as $name)
			$this->tags[strtolower($name)] = $tag;
		return $this;
	}

	public function getTagTemplateByName($name)
	{
		$name = strtolower($name);
		if (isset($this->tags[$name]))
			return $this->tags[$name];
		return null;
	}
	public function getTagTemplates()
	{
		return $this->tags;
	}

	public function createTag($name)
	{
		$tpl = $this->getTagTemplateByName($name);
		if ($tpl === null)
			throw new \Exception('Unknown tag : ' . $name);
		return new Tag($tpl, $name);
	}

	public function addTextFilter(TextFilterInterface $filter)
	{
		$this->filters[] = $filter;
		return $this;
	}

	public function getTextFilters()
	{
		return $this->filters;
	}

	public function setLenient($lenient)
	{
		$this->lenient = !!$lenient;
		return $this;
	}

	public function getLenient()
	{
		return $this->lenient;
	}
	public function isLenient()
	{
		return $this->lenient;
	}

	public function getParser()
	{
		if (!$this->parser)
			$this->parser = $this->createParser();
		return $this->parser;
	}

	public function parse($source)
	{
		return $this->getParser()->parse($source);
	}
	public function convertToHTML($source)
	{
		return $this->getParser()->convertToHTML($source);
	}

	public function createParser($documentClass = Parser::DEFAULT_DOCUMENT_CLASS, $tagClass = Parser::DEFAULT_TAG_CLASS, $textClass = Parser::DEFAULT_TEXT_CLASS)
	{
		return new Parser($this, $documentClass, $tagClass, $textClass);
	}
}
