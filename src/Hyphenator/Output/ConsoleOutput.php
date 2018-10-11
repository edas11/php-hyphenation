<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 09.45
 */

namespace Edvardas\Hyphenation\Hyphenator\Output;

use Edvardas\Hyphenation\UtilityComponents\Console\Console;

class ConsoleOutput implements HyphenationOutput
{
    private $console;

    public function __construct()
    {
        $this->console = new Console();
    }

    public function printLn(string $lineToPrint)
    {
        $this->console->printLn($lineToPrint);
    }

    public function printResult(array $result)
    {
        print_r($result);
    }

    public function printTime(float $executionTime)
    {
        echo "Finished in $executionTime seconds.\n";
    }

    public function printError(string $msg)
    {
        echo "Error: $msg";
    }
}