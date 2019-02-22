<?php

namespace Rainey\Spellchecker\Suggestion;

use Rainey\Spellchecker\Dictionary\DictionaryInterface;

class DamerauLevenshteinSuggestion implements SuggestionInterface
{

    protected $dictionaries;

    public function __construct(array $dictionaries = array())
    {
        $this->dictionaries = $dictionaries;
    }

    public function addDictionary(DictionaryInterface $dictionary)
    {
        array_push($this->dictionaries, $dictionary);
    }

    public function suggestion(string $word): array
    {
        return $this->findBest5(
            $this->calculate($word));
    }

    protected function calculate(string $word): array
    {
        $results = array();
        foreach ($this->dictionaries as $index => $dictionary) {
            while ($dictionary->next()) {
                $wordDictionary = $dictionary->current();
                print("dameareu $wordDictionary: </br>");
                print_r($this->damerauLevenshtein($word, $wordDictionary));
                $results[$wordDictionary] = $this->damerauLevenshtein($word, $wordDictionary);
            }
        }
        print("results: </br>");
        print_r($results);
        return $results;
    }

    protected function findBest5(array $array = array()): array
    {
        asort($array, SORT_NUMERIC);
        return array_slice($array, 0, 5, true);
    }

    protected function damerauLevenshtein($string1, $string2)
    {
        $length1 = strlen($string1);
        $length2 = strlen($string2);
        if ($length1 == 0 || $length2 == 0) {
            return max($length1, $length2);
        } else {
            $substitutionCost = ($string1[$length1 - 1] != $string2[$length2 - 1]) ? 1 : 0;
            $H1 = substr($string1, 0, $length1 - 1);
            $H2 = substr($string2, 0, $length2 - 1);
            if ($length1 > 1 && $length2 > 1 && $string1[$length1 - 1] == $string2[$length2 - 2] && $string1[$length1 - 2] == $string2[$length2 - 1]) {
                return min(
                    $this->damerauLevenshtein($H1, $string2) + 1,
                    $this->damerauLevenshtein($string1, $H2) + 1,
                    $this->damerauLevenshtein($H1, $H2) + $substitutionCost,
                    $this->damerauLevenshtein(substr($string1, 0, $length1 - 2), substr($string2, 0, $length2 - 2)) + 1
                );
            }
            return min(
                $this->damerauLevenshtein($H1, $string2) + 1,
                $this->damerauLevenshtein($string1, $H2) + 1,
                $this->damerauLevenshtein($H1, $H2) + $substitutionCost
            );
        }
    }

}
