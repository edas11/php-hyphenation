<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.3
 * Time: 14.49
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\Hyphenator\HyphenationNumbers;
use Edvardas\Hyphenation\Hyphenator\PatternHyphenationNumbers;

class WordHyphenationNumbers extends HyphenationNumbers implements \IteratorAggregate
{
    protected $numbersArray;

    public function __construct($wordGapsLength)
    {
        $this->numbersArray = array_fill(0, $wordGapsLength, 0);
    }

    protected function getNumbersArray(): array
    {
        return $this->numbersArray;
    }

    public function addWordNumbers(WordHyphenationNumbers $numbersToAdd)
    {
        $this->addNumbersArray($numbersToAdd->getNumbersArray());
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->numbersArray);
    }

    public static function createFromPatternNumbers(
        int $matchIndex,
        PatternHyphenationNumbers $numberPositionsInPattern,
        int $wordGapsLength
    ): WordHyphenationNumbers
    {
        $matchedNumbers = array_fill(0, $wordGapsLength, 0);
        foreach ($numberPositionsInPattern->getNumbersArray() as $index => $number) {
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
