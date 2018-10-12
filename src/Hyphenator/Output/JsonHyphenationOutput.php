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
    private $outputData = [];

    public function flush()
    {
        echo json_encode($this->outputData);
    }

    public function printResult(array $result)
    {
        if (array_key_exists('result', $this->outputData)) {
            array_push($this->outputData['result'], $result);
        } else {
            $this->outputData['result'] = $result;
        }
    }

    public function printTime(float $executionTime)
    {
        $this->outputData['executionTimeInSeconds'] = $executionTime;
    }

    public function printError(string $msg)
    {
        $this->outputData['error'] = $msg;
    }

    public function printMatchedPatterns(array $matchedPatterns)
    {
    }

    public function printHyphenatedWords(array $hyphenatedWords, array $skippedWords = [])
    {
        $this->outputData['hyphenatedWords'] = $hyphenatedWords;
        if (count($skippedWords) > 0) {
            $this->outputData['skippedWords'] = $skippedWords;
        }
    }
}