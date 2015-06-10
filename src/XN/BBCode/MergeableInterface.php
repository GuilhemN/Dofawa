<?php

namespace XN\BBCode;

interface MergeableInterface
{
    public function canMerge(MergeableInterface $other);

    public function inplaceMerge(MergeableInterface $other);
    public function merge(MergeableInterface $other);
}
