<?php

namespace XN\CodeGeneration;

class PHPEmitter
{
    private $source;
    private $indentLevel;
    private $indentPending;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->source = '';
        $this->indentLevel = 0;
        $this->indentPending = false;

        return $this;
    }

    public function save($file)
    {
        file_put_contents($file, $this->source);

        return $this;
    }
    public function load()
    {
        $tmp = tempnam(sys_get_temp_dir(), 'emt');
        $this->save($tmp);
        $unlinked = false;
        register_shutdown_function(function () use ($tmp, &$unlinked) {
            if (!$unlinked) {
                unlink($tmp);
            }
        });
        readfile($tmp); // echo
        $this->internalLoad($tmp);
        unlink($tmp);
        $unlinked = true;
    }
    private function internalLoad($tmp)
    {
        return include $tmp;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getIndentLevel()
    {
        return $this->indentLevel;
    }
    public function setIndentLevel($indentLevel)
    {
        $this->indentLevel = $indentLevel;

        return $this;
    }
    public function indent()
    {
        ++$this->indentLevel;

        return $this;
    }
    public function outdent()
    {
        --$this->indentLevel;

        return $this;
    }

    private function generateIndent()
    {
        return str_repeat("\t", $this->indentLevel);
    }

    public function emit($source)
    {
        if (strlen($source) == 0) {
            return $this;
        }
        if ($this->indentPending && substr_compare($source, "\n", 0, 1) != 0) {
            $source = $this->generateIndent().$source;
        }
        $this->indentPending = substr_compare($source, "\n", strlen($source) - 1, 1) == 0;
        $this->source .= preg_replace('/\n([^\n])/', "\n".$this->generateIndent().'\1', $source);

        return $this;
    }
    public function emitln($source = '')
    {
        $this->emit($source."\n");

        return $this;
    }
    public function emitRaw($source)
    {
        $this->source .= $source;

        return $this;
    }

    public function emitValue($value)
    {
        if (is_int($value) || is_float($value)) {
            return $this->emitNumber($value);
        } elseif ($value === null) {
            return $this->emit('null');
        } elseif (is_bool($value)) {
            return $this->emit($value ? 'true' : 'false');
        } elseif (is_array($value)) {
            $this->emit('[ ');
            $first = true;
            foreach ($value as $k => $v) {
                if ($first) {
                    $first = false;
                } else {
                    $this->emit(', ');
                }
                $this->emitValue($k);
                $this->emit(' => ');
                $this->emitValue($v);
            }
            $this->emit(' ]');

            return $this;
        } elseif (is_object($value)) {
            $this->emit('unserialize(');
            $this->emitString(serialize($value));
            $this->emit(')');
        } else {
            return $this->emitString($value);
        }
    }
    private function emitString($value)
    {
        $this->source .= sprintf('"%s"', addcslashes($value, "\0\t\"\$\\"));

        return $this;
    }
    private function emitNumber($value)
    {
        $locale = setlocale(LC_NUMERIC, '0');
        if ($locale !== false) {
            setlocale(LC_NUMERIC, 'C');
        }
        $this->source .= $value;
        if ($locale !== false) {
            setlocale(LC_NUMERIC, $locale);
        }

        return $this;
    }
}
