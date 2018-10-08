<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.3
 * Time: 13.52
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Algorithm;

use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Algorithm\WordHyphenationNumbers;
use Edvardas\Hyphenation\App\App;

abstract class AbstractHyphenationAlgorithm implements HyphenationAlgorithmInterface
{
    protected const REDUCE_CHARS = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '.'];

    private $patterns;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
        $patternTree = $this->parsePatternTree($patterns);
        App::$cache->set('patterns-tree', $patternTree);
    }

    abstract protected function parsePatternTree(array $patterns);

    public function execute(string $inputWord): string
    {
        $matchedNumbersAll = $this->getWordHyphenationNumbers($inputWord);
        $hyphenatedWords = $this->getHyphenatedWordsFromNumbers($inputWord, $matchedNumbersAll);
        return $hyphenatedWords;
    }

    private function getWordHyphenationNumbers(string $inputWord): WordHyphenationNumbers
    {
        App::$logger->info("Hyphenation on word $inputWord.");
        $matchedNumbersAll = new WordHyphenationNumbers(strlen($inputWord) - 1);
        for ($wordIndex = 0; $wordIndex < strlen($inputWord); $wordIndex++) {
            $possiblePatterns = $this->matchedPattern($inputWord, $wordIndex, $this->patternTree());
            foreach ($possiblePatterns as $pattern) {
                $matchedNumbers = $this->getPossiblePatternWordNumbers($inputWord, $pattern, $wordIndex);
                $matchedNumbersAll->addWordNumbers($matchedNumbers);
            }
        }
        return $matchedNumbersAll;
    }

    abstract protected function matchedPattern(string $inputWord, int $wordIndex, $patternTree, int $level = 0): array;

    private function getPossiblePatternWordNumbers(string $inputWord, string $pattern, int $wordIndex): WordHyphenationNumbers
    {
        $reducedPattern = str_replace(AbstractHyphenationAlgorithm::REDUCE_CHARS, '', $pattern);
        if ($this->begginingOrEndPatternFoundInMiddle($pattern, $reducedPattern, $inputWord, $wordIndex)) {
            return new WordHyphenationNumbers(strlen($inputWord) - 1);
        }
        App::$logger->info("Matched pattern $pattern");
        $numberPositionsInPattern = new PatternHyphenationNumbers($pattern);
        $matchedNumbers = WordHyphenationNumbers::createFromPatternNumbers(
            $wordIndex,
            $numberPositionsInPattern,
            strlen($inputWord) - 1
        );
        return $matchedNumbers;
    }

    private function getHyphenatedWordsFromNumbers(string $inputWord, WordHyphenationNumbers $numberInWord): string
    {
        $hyphenatedWords = $inputWord;
        $dashesNumber = 0;
        foreach ($numberInWord as $index => $number) {
            $cutPoint = $index + $dashesNumber;
            if ($this->isOdd((int)$number)) {
                $hyphenatedWords = substr($hyphenatedWords, 0, $cutPoint + 1)
                    . '-'
                    . substr($hyphenatedWords, $cutPoint + 1);
                $dashesNumber = $dashesNumber + 1;
            }
        }
        return $hyphenatedWords;
    }

    private function patternTree(): array
    {
        $tree = App::$cache->get('patterns-tree');
        if ($tree === null) {
            $tree = $this->parsePatternTree($this->patterns);
            App::$cache->set('patterns-tree', $tree);
        }
        return $tree;
    }

    private function begginingOrEndPatternFoundInMiddle(
        string $pattern,
        string $reducedPattern,
        $inputWord,
        int $matchIndex
    ): bool
    {
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

    private function isOdd(int $number): bool
    {
        if ($number % 2 === 1) {
            return true;
        }
        return false;
    }
}
