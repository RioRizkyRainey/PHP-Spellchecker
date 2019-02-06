<?php

namespace Rainey\Spellchecker;

use Rainey\Spellchecker\Dictionary\DictionaryInterface;

class Spellchecker
{
    protected $dictionary;

    public function __construct(DictionaryInterface $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    public function check(string $word): bool
    {
        return $this->dictionary->contains($word);
    }
}
