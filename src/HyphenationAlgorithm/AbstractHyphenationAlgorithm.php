<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.3
 * Time: 13.52
 */

namespace Edvardas\Hyphenation\HyphenationAlgorithm;

use Edvardas\Hyphenation\HyphenationAlgorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\HyphenationAlgorithm\WordHyphenationNumbers;
use Edvardas\Hyphenation\App\App;

abstract class AbstractHyphenationAlgorithm implements HyphenationAlgorithmInterface
{
    protected const REDUCE_CHARS = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '.'];

    public function __construct(array $patterns)
    {
        $patternTree = $this->parsePatternTree($patterns);
        App::$cache->set('patterns-tree', $patternTree);
    }

    abstract protected function parsePatternTree(array $patterns);

    public function execute(string $inputWord): string
    {
        $matchedNumbersAll = $this->getWordHyphenationNumbers($inputWord);
        $result = $this->getResultString($inputWord, $matchedNumbersAll);
        return $result;
    }

    protected function getWordHyphenationNumbers(string $inputWord): WordHyphenationNumbers
    {
        App::$logger->info("Hyphenation on word $inputWord.");
        $matchedNumbersAll = new WordHyphenationNumbers(strlen($inputWord) - 1);
        for ( $wordIndex=0; $wordIndex<strlen($inputWord); $wordIndex++ ) {
            $possiblePatterns=$this->matchedPattern($inputWord, $wordIndex, $this->patternTree());
            foreach ($possiblePatterns as $pattern) {
                $matchedNumbers = $this->getPossiblePatternWordNumbers($inputWord, $pattern, $wordIndex);
                $matchedNumbersAll->addWordNumbers($matchedNumbers);
            }
        }
        return $matchedNumbersAll;
    }

    abstract protected function matchedPattern(string $inputWord, int $wordIndex, $patternTree, int $level=0);

    abstract protected function getPossiblePatternWordNumbers(
        string $inputWord,
        $pattern,
        $wordIndex
    ): WordHyphenationNumbers;

    protected function getResultString(string $inputWord, WordHyphenationNumbers $numberInWord): string {
        $result = $inputWord;
        $dashesNumber = 0;
        foreach ($numberInWord as $index => $number) {
            $cutPoint = $index + $dashesNumber;
            if ($this->isOdd($number)) {
                $result = substr($result, 0, $cutPoint + 1)
                    . '-'
                    . substr($result, $cutPoint + 1);
                $dashesNumber = $dashesNumber + 1;
            }
        }
        return $result;
    }

    protected function patternTree(): array
    {
        $tree = App::$cache->get('patterns-tree');
        if ($tree === null) {
            throw new \Exception('Patterns tree not founc in cache');
        }
        return $tree;
    }

    protected function begginingOrEndPatternFoundInMiddle(
        string $pattern,
        string $reducedPattern,
        $inputWord,
        int $matchIndex
    ): bool {
        $beginingPatternNotInBegining = false;
        $endPatternNotInEnd = false;
        if ($pattern[0] === '.' && $matchIndex !== 0) {
            $beginingPatternNotInBegining = true;
        }
        if (
            $pattern[strlen($pattern) - 1] === '.'
            && $matchIndex !== (strlen($inputWord) - strlen($reducedPattern))
        ) {
            $endPatternNotInEnd = true;
        }
        return $beginingPatternNotInBegining || $endPatternNotInEnd;
    }

    protected function isOdd(int $number): bool
    {
        if ($number % 2 === 1) {
            return true;
        }
        return false;
    }
}
