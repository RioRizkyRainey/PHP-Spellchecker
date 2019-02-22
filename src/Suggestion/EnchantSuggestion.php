<?php

namespace Rainey\Spellchecker\Suggestion;

use Rainey\Spellchecker\Dictionary\DictionaryInterface;
use \Exception;

class EnchantSuggestion implements SuggestionInterface
{

    protected $ditcitonaryPath;
    protected $enchant;
    protected $language;

    public function __construct(string $ditcitonaryPath, string $language)
    {
        $this->ditcitonaryPath = $ditcitonaryPath;
        $this->language = $language;

        $this->enchant = enchant_broker_init();

        enchant_broker_set_dict_path($this->enchant, ENCHANT_MYSPELL, $ditcitonaryPath);
        enchant_broker_set_dict_path($this->enchant, ENCHANT_ISPELL, $ditcitonaryPath);
        enchant_broker_get_dict_path($this->enchant, ENCHANT_MYSPELL);
        enchant_broker_get_dict_path($this->enchant, ENCHANT_ISPELL);
    }

    public function addDictionary(DictionaryInterface $dictionary)
    {
        throw new Exception("Method not supported for Enchant Suggestion. \n Use constructor for set dictionary path");
    }

    public function suggestion(string $words): array
    {
        $suggestions = array();
        $lang = $this->normalizeLangCode();
        if (enchant_broker_dict_exists($this->enchant, $lang)) {
            $dict = enchant_broker_request_dict($this->enchant, $lang);

            $words = explode(" ", $words);

            foreach ($words as $word) {
                if (!enchant_dict_check($dict, $word)) {
                    $suggs = enchant_dict_suggest($dict, $word);

                    if (!is_array($suggs)) {
                        $suggs = array();
                    }

                    $suggestions[$word] = $suggs;
                }
            }

            enchant_broker_free_dict($dict);
            enchant_broker_free($this->enchant);
            return $suggestions;
        } else {
            enchant_broker_free($this->enchant);
            throw new Exception("Enchant spellchecker could not find dictionary for language: " . $lang);
        }
    }

    private function normalizeLangCode()
    {
        $variants = array(
            "en" => array("en_US", "en_GB"),
            "id" => array("id_ID"),
        );

        if (isset($variants[$this->language])) {
            array_unshift($variants, $this->language);

            foreach ($variants[$this->language] as $variant) {
                if (enchant_broker_dict_exists($this->enchant, $variant)) {
                    return $variant;
                }
            }
        }

        return $this->language;
    }

}
