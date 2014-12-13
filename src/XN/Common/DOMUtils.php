<?php
namespace XN\Common;

use \DOMDocument;
use \DOMElement;
use \DOMNode;

class DOMUtils
{
	public static function outerHTML(DOMNode $node = null)
	{
		if ($node === null)
			return '';
		$doc = new DOMDocument();
		$doc->appendChild($doc->importNode($node, true));
		return $doc->saveHTML();
	}
	
	public static function getElementsByClassName(DOMNode $node = null, $className, $maxLevel = null)
	{
		return self::getElementsBy($node, function ($child) use ($className) {
			return $child instanceof DOMElement && self::hasClass($child, $className);
		}, $maxLevel);
	}
	public static function getFirstElementByClassName(DOMNode $node = null, $className, $maxLevel = null)
	{
		return self::getFirstElementBy($node, function ($child) use ($className) {
			return $child instanceof DOMElement && self::hasClass($child, $className);
		}, $maxLevel);
	}
	public static function getElementsByNodeName(DOMNode $node = null, $nodeName, $maxLevel = null)
	{
		$nodeName = strtolower($nodeName);
		return self::getElementsBy($node, function ($child) use ($nodeName) {
			return strtolower($child->nodeName) == $nodeName;
		}, $maxLevel);
	}
	public static function getFirstElementByNodeName(DOMNode $node = null, $nodeName, $maxLevel = null)
	{
		$nodeName = strtolower($nodeName);
		return self::getFirstElementBy($node, function ($child) use ($nodeName) {
			return strtolower($child->nodeName) == $nodeName;
		}, $maxLevel);
	}
	
	public static function getElementsBy(DOMNode $node = null, $by, $maxLevel = null)
	{
		$elems = array();
		if ($node !== null)
			self::_getElementsBy($node, $by, $maxLevel, $elems);
		return $elems;
	}
	public static function getFirstElementBy(DOMNode $node = null, $by, $maxLevel = null)
	{
		if ($node === null || ($maxLevel !== null && $maxLevel <= 0))
			return null;
		$children = $node->childNodes;
		if (!$children)
			return null;
		$n = $children->length;
		for ($i = 0; $i < $n; ++$i) {
			$child = $children->item($i);
			if ($by($child))
				return $child;
			if (($child = self::getFirstElementBy($child, $by, ($maxLevel === null) ? null : ($maxLevel - 1))) !== null)
				return $child;
		}
		return null;
	}
	
	public static function getClasses(DOMElement $node = null)
	{
		if ($node === null)
			return array();
		return array_filter(explode(' ', $node->getAttribute('class')));
	}
	public static function hasClass(DOMElement $node = null, $className)
	{
		if ($node === null)
			return false;
		return in_array($className, self::getClasses($node));
	}
	
	private static function _getElementsBy(DOMNode $node, $by, $maxLevel, array &$elems)
	{
		if ($maxLevel !== null && $maxLevel <= 0)
			return;
		$children = $node->childNodes;
		if (!$children)
			return;
		$n = $children->length;
		for ($i = 0; $i < $n; ++$i) {
			$child = $children->item($i);
			if ($by($child))
				$elems[] = $child;
			self::_getElementsBy($child, $by, ($maxLevel === null) ? null : ($maxLevel - 1), $elems);
		}
	}
}