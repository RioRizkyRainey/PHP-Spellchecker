<?php

namespace Rainey\Spellchecker\Dictionary;

abstract class BaseDictionary implements DictionaryInterface
{

    protected $words;

    private $position = 0;

    abstract public function contains(string $word): bool;

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->words[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
        return $this->position < $this->count();
    }

    public function valid()
    {
        return isset($this->words[$this->position]);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->words[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->words[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->words[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->words[$offset]) ? $this->words[$offset] : null;
    }

}
