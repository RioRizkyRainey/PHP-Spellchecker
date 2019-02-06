<?php

namespace Rainey\Spellchecker\Dictionary;

use \SplFixedArray;

class SplFixedArrayDictionary extends BaseDictionary
{

    public function __construct(array $words = array())
    {
        $this->words = SplFixedArray::fromArray($words);
    }

    public function contains(string $word): bool
    {
        return in_array($word, $this->words->toArray());
    }

    public function count(): int
    {
        return count($this->words);
    }
    public function insertFromFile($filePath)
    {
        $words = explode("\n", file_get_contents($filePath));
        $this->words = SplFixedArray::fromArray($words);
    }
}
