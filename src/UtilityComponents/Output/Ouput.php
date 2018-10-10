<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.10
 * Time: 16.23
 */

namespace Edvardas\Hyphenation\UtilityComponents\Output;

interface Ouput
{
    public function printResult(array $result);

    public function printTime(float $executionTime);
}