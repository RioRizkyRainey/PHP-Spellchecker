<?php

namespace Rainey\Spellchecker\Dictionary;

class MapArrayDictionary extends BaseDictionary
{

    public function contains(string $word): bool
    {
        $firstCharacter = substr($word, 0, 1);
        return in_array($word, $this->words[$firstCharacter]);
    }

    public function count(): int
    {
        return count($this->words);
    }

    public function insertFromFile($filePath)
    {
        $this->words = array();
        $mapWords = explode("+++++++++\n", file_get_contents($filePath));
        for ($index = 0; $index < count($mapWords); $index++) {
            $words = explode("\n", $mapWords[$index]);
            $firstCharacter = substr($words[0], 0, 1);
            $this->words[$firstCharacter] = $words;
        }
    }

}
