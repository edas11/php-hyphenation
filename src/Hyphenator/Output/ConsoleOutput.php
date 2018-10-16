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

    public function printResult(array $result)
    {
        $this->console->printLn("Result:");
        print_r($result);
    }

    public function printTime(float $executionTime)
    {
        $this->console->printLn("Finished in $executionTime seconds.");
    }

    public function printError(string $msg)
    {
        $this->console->printLn("Error: $msg");
    }

    public function printMatchedPatterns(array $matchedPatterns)
    {
        $this->console->printLn("Matched patterns:");
        print_r($matchedPatterns);
    }

    public function printSkippedWords(array $skippedWords)
    {
        $this->console->printLn("Skipped words:");
        print_r($skippedWords);
    }

    public function printHyphenatedWords(array $hyphenatedWords)
    {
        $this->console->printLn("Hyphenated words:");
        print_r($hyphenatedWords);
    }
}