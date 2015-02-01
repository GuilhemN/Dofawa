<?php

namespace Dof\Common;

use XN\Exception as Exceptions;

class D2PContentProvider
{
    /**
     * array<Stream>
     */
    private $fs;

    /**
     * array<string, Range>
     */
    private $toc;

    public function process($path) {
        $path = realpath($path);
        if(!file_exists($path))
            throw new \LogicException('Fichier D2P n\'existe pas.');

        $this->fs = [];
        $this->toc = [];
        $dir = dirname($path);

        $idx = -1;
        $bpos = 0; $fpos = 0; $fcnt = 0; $ppos = 0; $pcnt = 0; $key = '';

        while ($path !== null) {
            $fs = fopen($path, 'r');
            $path = null;
            $this->fs[] = $fs;
            $idx++;

            $buf = fread($fs, 2);
            if (strlen($buf) < 2)
                throw new Exceptions\EndOfStreamException(sprintf('Erreur ligne %s', __LINE__));
            if($buf[0] != "\x02" || $buf[1] != "\x01")
                throw new \Exception('invalid data');
            fseek($fs, -24, SEEK_END);
            $buf = fread($fs, 24);
            if (strlen($buf) < 24)
                throw new Exceptions\EndOfStreamException(sprintf('Erreur ligne %s', __LINE__));
            $values = unpack('Nbpos/Ndummy/Nfpos/Nfcnt/Nppos/Npcnt', $buf);
            $bpos = $values['bpos'];
            $fpos = $values['fpos'];
            $fcnt = $values['fcnt'];
            $ppos = $values['ppos'];
            $pcnt = $values['pcnt'];
            fseek($fs, $ppos);
            for($i = 0; $i < $pcnt; ++$i) {
                $buf = fread($fs, 2);
                if(strlen($buf) < 2)
                    throw new Exceptions\EndOfStreamException(sprintf('Erreur ligne %s', __LINE__));
                $length = unpack('nlength', $buf)['length'];
                $key = fread($fs, $length);
                if(strlen($key) < $length)
                    throw new Exceptions\EndOfStreamException(sprintf('Erreur ligne %s', __LINE__));
                $buf = fread($fs, 2);
                if(strlen($buf) < 2)
                    throw new Exceptions\EndOfStreamException(sprintf('Erreur ligne %s', __LINE__));
                $length = unpack('nlength', $buf)['length'];
                $path = fread($fs, $length);
                if(strlen($path) < $length)
                    throw new Exceptions\EndOfStreamException(sprintf('Erreur ligne %s', __LINE__));
                if($key == 'link')
                    if(!empty($dir))
                        $path = join(DIRECTORY_SEPARATOR, array($dir, $path));
            }
            fseek($fs, $fpos);
            for($i = 0; $i < $fcnt; ++$i) {
                $buf = fread($fs, 2);
                if(strlen($buf) < 2)
                    throw new Exceptions\EndOfStreamException(sprintf('Erreur ligne %s', __LINE__));
                $length = unpack('nlength', $buf)['length'] + 8;
                $buf = fread($fs, $length);
                if(strlen($buf) < $length)
                    throw new Exceptions\EndOfStreamException(sprintf('Erreur ligne %s', __LINE__));
                $this->toc[substr($buf, 0, $length - 8)] = [
                    'stream' => $idx,
                    'offset' => $bpos + unpack('Nint', substr($buf, $length - 8, 4))['int'],
                    'length' => unpack('Nint', substr($buf, $length - 4, 4))['int'],
                ];
            }
        }
    }

    public function open($name) {
        if(!isset($this->toc[$name]))
            return null;
        $values = $this->toc[$name];
        $fs = $this->fs[$values['stream']];
        fseek($fs, $values['offset']);
        $buf = fread($fs, $values['length']);
        if(strlen($buf) < $values['length'])
            throw new Exceptions\EndOfStreamException(sprintf('Erreur ligne %s', __LINE__));
        $h = fopen('php://memory', "rw+");
        fwrite($h, $buf);
        fseek($h, 0);
        return $h;
    }

    public function enumerate() {
        return array_keys($this->toc);
    }

    public function close() {
        foreach($this->fs as $fs)
            fclose($fs);
    }
}
