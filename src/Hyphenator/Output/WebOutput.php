<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.10
 * Time: 16.20
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Output;

class WebOutput implements HyphenationOutput
{
    private $contentType = 'application/json';
    private $pagePath = '';
    private $outputData = [];

    public function flush()
    {
        if ($this->contentType === 'application/json') {
            echo json_encode($this->outputData);
        } else {
            $this->includeHtmlPage();
        }
    }

    private function includeHtmlPage(): void
    {
        $data = $this->outputData;
        require $this->pagePath;
    }

    public function configureOutput(string $contentType, string $pagePath = '')
    {
        header("content-type: $contentType");
        $this->contentType = $contentType;
        $this->pagePath = $pagePath;
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

    public function printSkippedWords(array $skippedWords)
    {
        $this->outputData['skippedWords'] = $skippedWords;
    }

    public function printHyphenatedWords(array $hyphenatedWords)
    {
        $this->outputData['hyphenatedWords'] = $hyphenatedWords;
    }
}