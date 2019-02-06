<?php

namespace Rainey\Spellchecker\Test;

use PHPUnit\Framework\TestCase;
use Rainey\Spellchecker\Dictionary\MapArrayDictionary;
use Rainey\Spellchecker\Dictionary\SplFixedArrayDictionary;
use Rainey\Spellchecker\Spellchecker;

class SpellcheckerTest extends TestCase
{

    protected $dictionary;

    protected $dictionaryMapArray;

    protected $spellchecker;

    protected $spellcheckerMapArray;

    public function setUp()
    {
        $this->dictionary = new SplFixedArrayDictionary();
        $this->dictionary->insertFromFile(__DIR__ . "/../asset/id");

        $this->spellchecker = new Spellchecker($this->dictionary);

        $this->dictionaryMapArray = new MapArrayDictionary();
        $this->dictionaryMapArray->insertFromFile(__DIR__ . "/../asset/id-map");

        $this->spellcheckerMapArray = new Spellchecker($this->dictionaryMapArray);
    }

    public function testSpellcheckerSplFixedArray()
    {
        $this->assertTrue($this->spellchecker->check('berkabar'));
        $this->assertFalse($this->spellchecker->check('wikwak'));
    }

    public function testSpellcheckerMapArray()
    {
        $this->assertTrue($this->spellcheckerMapArray->check('berkabar'));
        $this->assertFalse($this->spellcheckerMapArray->check('wikwak'));
    }

}
