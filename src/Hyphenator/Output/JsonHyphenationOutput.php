<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.10
 * Time: 16.20
 */

namespace Edvardas\Hyphenation\Hyphenator\Output;


class JsonHyphenationOutput implements HyphenationOutput
{
    public function printResult(array $result)
    {
        echo json_encode(['hyphenatedWords' => $result]);
    }

    public function printTime(float $executionTime)
    {
        //echo json_encode(['executionTimeInSeconds' => $executionTime]);
    }
}