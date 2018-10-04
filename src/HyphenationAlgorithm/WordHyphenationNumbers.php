<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.3
 * Time: 14.49
 */

namespace Edvardas\Hyphenation\HyphenationAlgorithm;

use Edvardas\Hyphenation\HyphenationAlgorithm\HyphenationNumbers;
use Edvardas\Hyphenation\HyphenationAlgorithm\PatternHyphenationNumbers;

class WordHyphenationNumbers implements HyphenationNumbers, \IteratorAggregate
{
    protected $numbersArray;

    public function __construct($wordGapsLength)
    {
        $this->numbersArray = array_fill(0, $wordGapsLength, 0);
    }

    public function get(): array
    {
        return $this->numbersArray;
    }

    public function addWordNumbers(WordHyphenationNumbers $numbersToAdd)
    {
        $this->addNumbersArray($numbersToAdd->get());
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->numbersArray);
    }

    public static function createFromPatternNumbers(
        int $matchIndex,
        PatternHyphenationNumbers$numberPositionsInPattern,
        int $wordGapsLength
    ): WordHyphenationNumbers {
        $matchedNumbers = array_fill(0, $wordGapsLength, 0);
        foreach ($numberPositionsInPattern->get() as $index => $number) {
            $gapIndexInWord = $index - 1 + $matchIndex;
            $matchedNumbers[$gapIndexInWord] = $number;
        }
        $matchedPatternNumbersInWord = new WordHyphenationNumbers($wordGapsLength);
        $matchedPatternNumbersInWord->addNumbersArray($matchedNumbers);
        return $matchedPatternNumbersInWord;
    }

    private function addNumbersArray(array $numbersToAddArray)
    {
        foreach ($this->numbersArray as $index => $currentNumber) {
            $isNewNumberBigger = $numbersToAddArray[$index] > $currentNumber;
            $this->numbersArray[$index] = $isNewNumberBigger ? $numbersToAddArray[$index] : $currentNumber;
        }
    }
}
