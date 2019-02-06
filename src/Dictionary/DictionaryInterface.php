<?php

namespace Rainey\Spellchecker\Dictionary;

use \ArrayAccess;
use \Countable;
use \Iterator;

interface DictionaryInterface extends Countable, Iterator, ArrayAccess
{
    public function contains(string $word): bool;
}
