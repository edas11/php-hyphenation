<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 09.15
 */

namespace Edvardas\Hyphenation\Output;

class ConsoleOutput
{
    public function printResult(array $result)
    {
        print_r($result);
    }

    public function printTime(float $executionTime)
    {
        echo "Finished in $executionTime seconds.\n";
    }

}