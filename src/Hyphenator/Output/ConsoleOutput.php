<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 09.45
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Output;

use Edvardas\Hyphenation\UtilityComponents\Console\Console;

class ConsoleOutput implements BufferedOutput
{
    private $outputData = [];
    private $console;

    public function __construct()
    {
        $this->console = new Console();
    }

    public function set(string $key, $data): void
    {
        $this->outputData[$key] = $data;
    }

    public function flush(): void
    {
        foreach ($this->outputData as $key => $dataValue) {
            switch ($key) {
                case "result":
                    $this->printResult($dataValue);
                    break;
                case "time":
                    $this->printTime($dataValue);
                    break;
                case "error":
                    $this->printError($dataValue);
                    break;
                case "matchedPatterns":
                    $this->printMatchedPatterns($dataValue);
                    break;
                case "skippedWords":
                    $this->printSkippedWords($dataValue);
                    break;
                case "hyphenatedWords":
                    $this->printHyphenatedWords($dataValue);
                    break;
            }
        }
    }

    private function printResult($result)
    {
        $this->console->printLn("Result:");
        print_r($result);
    }

    private function printTime($executionTime)
    {
        $this->console->printLn("Finished in $executionTime seconds.");
    }

    private function printError($msg)
    {
        $this->console->printLn("Error: $msg");
    }

    private function printMatchedPatterns($matchedPatterns)
    {
        $this->console->printLn("Matched patterns:");
        print_r($matchedPatterns);
    }

    private function printSkippedWords($skippedWords)
    {
        $this->console->printLn("Skipped words:");
        print_r($skippedWords);
    }

    private function printHyphenatedWords($hyphenatedWords)
    {
        $this->console->printLn("Hyphenated words:");
        print_r($hyphenatedWords);
    }
}