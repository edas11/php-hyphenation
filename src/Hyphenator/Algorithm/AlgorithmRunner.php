<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 10.44
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Algorithm;

class AlgorithmRunner
{
    private $algorithm;
    private $hyphenatedWords = [];
    private $matchedPatternsAll = [];

    public function __construct(AbstractHyphenationAlgorithm $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function run(array $words)
    {
        foreach ($words as $inputWord) {
            $hyphenatedWord = $this->algorithm->execute($inputWord);
            $this->hyphenatedWords[$inputWord] = $hyphenatedWord;
        }
    }

    public function runAndSavePatterns(array $words)
    {
        foreach ($words as $inputWord) {
            $hyphenatedWord = $this->algorithm->executeAndSavePatterns($inputWord);
            $this->matchedPatternsAll[$inputWord] = $this->algorithm->getMatchedPatterns();
            $this->hyphenatedWords[$inputWord] = $hyphenatedWord;
        }
    }

    public function getHyphenatedWords(): array
    {
        return $this->hyphenatedWords;
    }

    public function getMatchedPatterns(): array
    {
        return $this->matchedPatternsAll;
    }
}