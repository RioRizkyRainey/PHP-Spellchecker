<?php

namespace Rainey\Spellchecker\Suggestion;

use Rainey\Spellchecker\Dictionary\DictionaryInterface;
use \Exception;

class EnchantSuggestion implements SuggestionInterface
{

    protected $ditcitonaryPath;
    protected $enchant;
    protected $language;
    protected $dictionary;

    public function __construct(string $ditcitonaryPath, string $language)
    {
        $this->ditcitonaryPath = $ditcitonaryPath;
        $this->language = $language;

        $this->enchant = enchant_broker_init();

        $this->language = $this->normalizeLangCode();

        enchant_broker_set_dict_path($this->enchant, ENCHANT_MYSPELL, $ditcitonaryPath);
        enchant_broker_set_dict_path($this->enchant, ENCHANT_ISPELL, $ditcitonaryPath);
        enchant_broker_get_dict_path($this->enchant, ENCHANT_MYSPELL);
        enchant_broker_get_dict_path($this->enchant, ENCHANT_ISPELL);

        $this->dictionary = enchant_broker_request_dict($this->enchant, $this->language);
    }

    public function addDictionary(DictionaryInterface $dictionary)
    {
        throw new Exception("Method not supported for Enchant Suggestion. \n Use constructor for set dictionary path");
    }

    public function suggestion(string $word): array
    {
        $suggestions = array();
        if (!enchant_dict_check($this->dictionary, $word)) {
            $suggs = enchant_dict_suggest($this->dictionary, $word);

            if (!is_array($suggs)) {
                $suggs = array();
            }

            $suggestions = $suggs;
        }

        return $suggestions;
    }

    public function isDictionaryExist()
    {
        return enchant_broker_dict_exists($this->enchant, $this->language);
    }

    public function freeBroker()
    {
        enchant_broker_free_dict($this->dictionary);
        enchant_broker_free($this->enchant);
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
