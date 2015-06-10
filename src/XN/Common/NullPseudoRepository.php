<?php

namespace XN\Common;

class NullPseudoRepository
{
    public function find()
    {
        return;
    }
    public function findOneBy()
    {
        return;
    }
    public function findBy()
    {
        return [];
    }
    public function findAll()
    {
        return [];
    }
}
