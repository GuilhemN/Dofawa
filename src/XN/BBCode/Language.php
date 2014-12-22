<?php
namespace XN\BBCode;

class Language
{
	private $tags;
	private $parser;

	public function __construct()
	{
		$this->tags = [ ];
	}

	public function registerTagTemplate(TagTemplateInterface $tag)
	{
		foreach ($tag->getNames() as $name)
			$this->tags[strtolower($name)] = $tag;
	}

	public function getTagTemplateByName($name)
	{
		$name = strtolower($name);
		if (isset($this->tags[$name]))
			return $this->tags[$name];
		return null;
	}

	public function createTag($name)
	{
		$tpl = $this->getTagTemplateByName($name);
		if ($tpl === null)
			throw new \Exception('Unknown tag : ' . $name);
		return new Tag($tpl, $name);
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
