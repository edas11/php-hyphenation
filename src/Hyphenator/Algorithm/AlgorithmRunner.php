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

    public function run(array $words, bool $saveMatchedPatterns = false)
    {
        $hyphenatedWords = [];
        $matchedPatternsAll = [];
        foreach ($words as $inputWord) {
            $hyphenatedWord = $this->algorithm->execute($inputWord, $saveMatchedPatterns);
            $matchedPatternsAll[$inputWord] = $this->algorithm->getMatchedPatterns();
            $hyphenatedWords[$inputWord] = $hyphenatedWord;
        }
        $this->hyphenatedWords = $hyphenatedWords;
        $this->matchedPatternsAll = $matchedPatternsAll;
    }

    /**
     * @return string[]
     */
    public function getHyphenatedWords(): array
    {
        return $this->hyphenatedWords;
    }

    /**
     * @return string[][]
     */
    public function getMatchedPatterns(): array
    {
        return $this->matchedPatternsAll;
    }
}