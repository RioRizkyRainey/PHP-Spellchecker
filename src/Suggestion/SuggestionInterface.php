<?php

namespace Rainey\Spellchecker\Suggestion;

use Rainey\Spellchecker\Dictionary\DictionaryInterface;

interface SuggestionInterface
{
    public function addDictionary(DictionaryInterface $dictionary);
    public function suggestion(string $word): array;
}
