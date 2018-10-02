<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.2
 * Time: 11.50
 */

class HyphenationAlgorithm
{
    private $reducedPatterns;
    /**
     * @param $patterns
     * @param $inputWord
     * @return string
     */
    function execute($patterns, $inputWord): string
    {
        $matchedNumbersAll = $this->getMatchedPatternsNumbers($patterns, $inputWord);
        $result = $this->getResultString($inputWord, $matchedNumbersAll);
        return $result;
    }

    /**
     * @param $patterns
     * @param $inputWord
     * @return array
     */
    private function getMatchedPatternsNumbers(array $patterns, string $inputWord): array
    {
        $matchedNumbersAll = array_fill(0, strlen($inputWord) - 1, 0);
        $reduceChar = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '.'];
        $match = false;
        $patternTree = $this->parseShortPatternTree($patterns);
        for ( $wordIndex=0; $wordIndex<strlen($inputWord); $wordIndex++ ) {
            $possiblePatterns = $patternTree[$inputWord[$wordIndex]];
            foreach ($possiblePatterns as $pattern) {
                $reducedPattern = str_replace($reduceChar, '', $pattern);
                $found = stripos($inputWord, $reducedPattern, $wordIndex);
                if ($found !== false) {
                    $matchedNumbers = array_fill(0, strlen($inputWord) - 1, 0);
                    if ($pattern[0] === '.' && $found !== 0) {
                        continue;
                    }
                    if (
                        $pattern[strlen($pattern) - 1] === '.'
                        && $found !== (strlen($inputWord) - strlen($reducedPattern))
                    ) {
                        continue;
                    }
                    $match = true;

                    $numberPositionsInPattern = $this->numberPositionsInPattern($pattern);
                    $matchedNumbers = $this->numbersOfOneMatch($found, $numberPositionsInPattern, strlen($inputWord) - 1);
                }

                if ($match) {
                    $matchedNumbersAll = $this->addMatchedNumbers($matchedNumbersAll, $matchedNumbers);
                    $match = false;
                }

            }
        }
        return $matchedNumbersAll;
    }

    /**
     * @param $inputWord
     * @param $numberInWord
     * @return string
     */
    private function getResultString(string $inputWord, array $numberInWord): string
    {
        $result = $inputWord;
        $dashesNumber = 0;
        foreach ($numberInWord as $index => $number) {
            $cutPoint = $index + $dashesNumber;
            if ($this->isOdd($number)) {
                $result = substr($result, 0, $cutPoint + 1) . '-' . substr($result, $cutPoint + 1);
                $dashesNumber = $dashesNumber + 1;
            }
        }
        return $result;
    }

    // pattern gap index => pattern number. Pattern is considered to start with a gap.
    private function numberPositionsInPattern(string $pattern): array
    {
        $patternNoPoint = str_replace('.', '', $pattern);
        $patternExpaned = $this->expandPatternGaps($patternNoPoint);

        $numberPos = [];
        for ($j = 0; $j < strlen($patternExpaned); $j++) {
            if (is_numeric($patternExpaned[$j])) {
                $numberPos[$j / 2] = $patternExpaned[$j];
            };
        }
        return $numberPos;
    }

    /**
     * @param $patternNoPoint
     * @return string
     */
    private function expandPatternGaps($patternNoPoint): string
    {
        if (!is_numeric($patternNoPoint[0])) {
            $patternExpaned = ' ';
        } else {
            $patternExpaned = '';
        }
        for ($i = 0; $i < strlen($patternNoPoint); $i++) {
            if (is_numeric($patternNoPoint[$i])) {
                $patternExpaned = $patternExpaned . $patternNoPoint[$i];
            } elseif (
                $i + 1 < strlen($patternNoPoint)
                && !is_numeric($patternNoPoint[$i + 1])) {
                $patternExpaned = $patternExpaned . $patternNoPoint[$i] . ' ';
            } else {
                $patternExpaned = $patternExpaned . $patternNoPoint[$i];
            }
        }
        return $patternExpaned;
    }

    // word gap index => pattern number
    private function numbersOfOneMatch($found, $numberPositionsInPattern, $wordGapsLength): array
    {
        $matchedNumbers = array_fill(0, $wordGapsLength, 0);
        foreach ($numberPositionsInPattern as $index => $number) {
            $gapIndexInWord = $index - 1 + $found;
            $matchedNumbers[$gapIndexInWord] = $number;
        }
        return $matchedNumbers;
    }

    private function addMatchedNumbers(array $currentNumbers, array $numbersToAdd): array
    {
        foreach ($currentNumbers as $index => $number) {
            $newNumbers[$index] = ($numbersToAdd[$index] > $number) ? $numbersToAdd[$index] : $number;
        }
        return $newNumbers;
    }

    private function isOdd(int $number): bool
    {
        if ($number % 2 === 1) {
            return true;
        }
        return false;
    }
    private function parseShortPatternTree(array $patterns): array {
        $shortPatternsTree = [];
        $reduceChar = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '.'];
        foreach ($patterns as $index=>$pattern) {
            $this->reducedPatterns[$index] = str_replace($reduceChar, '', $pattern);
            $firstLetter = $this->reducedPatterns[$index][0];
            if (!array_key_exists( (string)$firstLetter, $shortPatternsTree )) {
                $shortPatternsTree[(string)$firstLetter] = [];
            }
            array_push($shortPatternsTree[$firstLetter], $pattern);
        }
        return $shortPatternsTree;
    }
}