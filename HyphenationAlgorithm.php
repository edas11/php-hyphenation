<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.2
 * Time: 11.50
 */

class HyphenationAlgorithm
{
    private $patternTree;
    /**
     * @param $patterns
     * @param $inputWord
     * @return string
     */

    public function __construct(array $patterns)
    {
        //$this->patternTree = $this->parseShortPatternTree($patterns);
        $this->patternTree = $this->parsePatternTree($patterns);
    }

    function execute($inputWord): string
    {
        $matchedNumbersAll = $this->getMatchedPatternsNumbers($inputWord);
        $result = $this->getResultString($inputWord, $matchedNumbersAll);
        return $result;
    }

    /**
     * @param $patterns
     * @param $inputWord
     * @return array
     */
    private function getMatchedPatternsNumberss(string $inputWord): array
    {
        $matchedNumbersAll = array_fill(0, strlen($inputWord) - 1, 0);
        $reduceChar = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '.'];
        $match = false;
        for ( $wordIndex=0; $wordIndex<strlen($inputWord); $wordIndex++ ) {
            $possiblePatterns = $this->patternTree[$inputWord[$wordIndex]];
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
            $reducedPattern = str_replace($reduceChar, '', $pattern);
            $firstLetter = $reducedPattern[0];
            if (!array_key_exists( (string)$firstLetter, $shortPatternsTree )) {
                $shortPatternsTree[(string)$firstLetter] = [];
            }
            array_push($shortPatternsTree[$firstLetter], $pattern);
        }
        return $shortPatternsTree;
    }


    // With full pattern tree, currently not working correctly
    private function getMatchedPatternsNumbers(string $inputWord): array
    {
        $matchedNumbersAll = array_fill(0, strlen($inputWord) - 1, 0);
        $reduceChar = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '.'];
        $match = false;
        //var_dump($this->patternTree['n']['s']);
        for ( $wordIndex=0; $wordIndex<strlen($inputWord); $wordIndex++ ) {
            $patterns=$this->matchedPattern($inputWord, $wordIndex, $this->patternTree);
                //var_dump($patterns);
                foreach ($patterns as $pattern) {
                    $reducedPattern = str_replace($reduceChar, '', $pattern);
                    //$found = stripos($inputWord, $reducedPattern, $wordIndex);
                    $matchedNumbers = array_fill(0, strlen($inputWord) - 1, 0);
                    if ($pattern[0] === '.' && $wordIndex !== 0) {
                        continue;
                    }
                    if (
                        $pattern[strlen($pattern) - 1] === '.'
                        && $wordIndex !== (strlen($inputWord) - strlen($reducedPattern))
                    ) {
                        continue;
                    }
                    $match = true;
                    $numberPositionsInPattern = $this->numberPositionsInPattern($pattern);
                    $matchedNumbers = $this->numbersOfOneMatch($wordIndex, $numberPositionsInPattern, strlen($inputWord) - 1);
                    if ($match) {
                        $matchedNumbersAll = $this->addMatchedNumbers($matchedNumbersAll, $matchedNumbers);
                        $match = false;
                    }
                }
        }
        return $matchedNumbersAll;
    }
    private function parsePatternTree(array $patterns): array {
        $patternsTree = [];
        $reduceChar = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '.'];
        foreach ($patterns as $index=>$pattern) {
            $reducedPattern = str_replace($reduceChar, '', $pattern);
            $this->putPatternToTree($pattern, $reducedPattern, $patternsTree);
        }
        return $patternsTree;
    }
    private function putPatternToTree(string $pattern, string $reducedPattern, array &$patternsTree, int $level=0) {
        if ($level === strlen($reducedPattern)) {
            if (!array_key_exists(0, $patternsTree)) {
                $patternsTree[0] = new Patterns();
            }
            $patternsTree[0]->add($pattern);
            return;
        }
        $letter = (string)$reducedPattern[$level];
        if (!array_key_exists( $letter, $patternsTree )) {
            $patternsTree[$letter] = [];
        }
        $this->putPatternToTree($pattern, $reducedPattern, $patternsTree[$letter], $level+1);
    }
    private function matchedPattern(string $inputWord, int $wordIndex, $patternTree, int $level=0) {
        $currentIndex = $wordIndex + $level;
        if ($currentIndex > strlen($inputWord)) {
            return [];
        }

        $patternsOfThisLevel = new Patterns();
        if (array_key_exists(0, $patternTree)) {
            $patternsOfThisLevel->addAll($patternTree[0]->get());
        }

        $patternsOfNextLevels = new Patterns();
        if ($currentIndex < strlen($inputWord)) {
            $letter = $inputWord[$currentIndex];
            if (array_key_exists($letter, $patternTree)) {
                $patternsOfNextLevels->addAll(
                    $this->matchedPattern($inputWord, $wordIndex, $patternTree[(string)$letter], $level + 1)
                );
            }
        }

        $patternsOfThisLevel->addAll($patternsOfNextLevels->get());
        return $patternsOfThisLevel->get();
    }
}