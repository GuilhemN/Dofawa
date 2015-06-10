<?php

namespace XN\Common;

use XN\Grammar\Reader;

class Unpacker
{
    private $re;

    public function __construct(Reader $re)
    {
        $this->re = $re;
    }

    public function readBoolean()
    {
        return $this->re->read(1) != "\0";
    }

    public function readUnsignedByte()
    {
        return $this->readPacked('C', 1);
    }
    public function readByte()
    {
        return $this->readPacked('c', 1);
    }

    public function readBytes($offset, $length)
    {
        $bytes = [];
        $this->re->skip($offset);
        for ($i = 0; $i < $length; $i++) {
            $bytes[] = $this->readByte();
        }

        return $bytes;
    }

    public function readUnsignedInt()
    {
        return $this->readPacked('N', 4);
    }
    public function readInt()
    {
        $ex = (PHP_INT_SIZE - 4) << 3;

        return ($this->readUnsignedInt() << $ex) >> $ex;
    }

    public function readUnsignedShort()
    {
        return $this->readPacked('n', 2);
    }
    public function readShort()
    {
        $ex = (PHP_INT_SIZE - 2) << 3;

        return ($this->readUnsignedShort() << $ex) >> $ex;
    }

    public function readPacked($code, $cb)
    {
        list(, $val) = unpack($code, $this->re->read($cb));

        return $val;
    }
}
