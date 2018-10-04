<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.3
 * Time: 16.52
 */

namespace Edvardas\Hyphenation\HyphenationAlgorithm;

abstract class HyphenationNumbers
{
    protected $numbersArray;

    protected function get(): array
    {
        return $this->numbersArray;
    }
}
