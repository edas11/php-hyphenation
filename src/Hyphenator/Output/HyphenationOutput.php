<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.10
 * Time: 16.23
 */

namespace Edvardas\Hyphenation\Hyphenator\Output;

interface HyphenationOutput
{
    public function printResult(array $result);

    public function printTime(float $executionTime);
}