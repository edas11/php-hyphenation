<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.3
 * Time: 16.52
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\HyphenationAlgorithm;

abstract class HyphenationNumbers
{
    abstract protected function getNumbersArray(): array;
}
