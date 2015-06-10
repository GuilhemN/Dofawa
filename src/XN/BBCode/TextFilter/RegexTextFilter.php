<?php

namespace XN\BBCode\TextFilter;

use XN\BBCode\Text;
use XN\BBCode\TextFilterInterface;

abstract class RegexTextFilter implements TextFilterInterface
{
    public function matchAll(Text $source)
    {
        $offset = $source->getOffset();
        $source = $source->getValue();
        if (!preg_match_all($this->getRegex(), $source, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE)) {
            return [];
        }

        return array_map(function (array $match) use ($offset) {
            $relOffset = $match[0][1];
            $offset += $relOffset;
            foreach ($match as &$group) {
                $group[1] -= $offset;
            }

            return [$relOffset, strlen($match[0][0]), $this->hydrate($match, $offset)];
        }, $matches);
    }

    abstract public function getRegex();

    abstract protected function hydrate(array $match, $offset);
}
