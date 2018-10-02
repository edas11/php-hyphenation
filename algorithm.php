<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.2
 * Time: 11.50
 */

/**
 * @param $patterns
 * @param $inputWord
 * @return string
 */
function hyphenationAlgorithm($patterns, $inputWord): string
{
    $matchedNumbersAll = getMatchedPatternsNumbers($patterns, $inputWord);

//var_dump($matchedNumbersAll);
    $numbersInWord = array_fill(0, strlen($inputWord) - 1, 0);
    foreach ($matchedNumbersAll as $matchedNumbers) {
        $numbersInWord = addMatchedNumbers($numbersInWord, $matchedNumbers);
    }
//var_dump($numberInWord);
    $result = getResultString($inputWord, $numbersInWord);
    return $result;
}

/**
 * @param $patterns
 * @param $inputWord
 * @return array
 */
function getMatchedPatternsNumbers(array $patterns, string $inputWord): array
{
    $matchedNumbersAll = [];
    $reduceChar = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '.'];
    foreach ($patterns as $pattern) {
        $reducedPattern = str_replace($reduceChar, '', $pattern);
        $numberPositionsInPattern = numberPositionsInPattern($pattern);


        $found = -1;
        $matchedNumbers = array_fill(0, strlen($inputWord) - 1, 0);
        while (($found = stripos($inputWord, $reducedPattern, $found + 1)) !== false) {
            //echo $pattern."\n";
            if ($pattern[0] === '.' && $found !== 0) {
                break;
            }
            if (
                $pattern[strlen($pattern) - 1] === '.'
                && $found !== (strlen($inputWord) - strlen($reducedPattern))
            ) {
                break;
            }

            $matchedNumbers = addMatchedNumbers(
                $matchedNumbers,
                numbersOfOneMatch($found, $numberPositionsInPattern, strlen($inputWord) - 1)
            );
        }
        if (array_sum($matchedNumbers) > 0) array_push($matchedNumbersAll, $matchedNumbers);
    }
    return $matchedNumbersAll;
}
/**
 * @param $inputWord
 * @param $numberInWord
 * @return string
 */
function getResultString(string $inputWord, array $numberInWord): string
{
    $result = $inputWord;
    $dashesNumber = 0;
    foreach ($numberInWord as $index => $number) {
        $cutPoint = $index + $dashesNumber;
        if (isOdd($number)) {
            $result = substr($result, 0, $cutPoint + 1) . '-' . substr($result, $cutPoint + 1);
            $dashesNumber = $dashesNumber + 1;
        }
    }
    return $result;
}
// pattern gap index => pattern number. Pattern is considered to start with a gap.
function numberPositionsInPattern(string $pattern): array {
    $patternNoPoint = str_replace('.', '', $pattern);
    $patternExpaned = expandPatternGaps($patternNoPoint);

    $numberPos = [];
    for ( $j = 0; $j < strlen($patternExpaned); $j++){
        if (is_numeric($patternExpaned[$j])) {
            $numberPos[$j/2] = $patternExpaned[$j];
        };
    }
    return $numberPos;
}

/**
 * @param $patternNoPoint
 * @return string
 */
function expandPatternGaps($patternNoPoint): string
{
    if ( !is_numeric($patternNoPoint[0]) ) {
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
function numbersOfOneMatch($found, $numberPositionsInPattern, $wordGapsLength): array {
//    var_dump($found, $numberPositionsInPattern);
    $matchedNumbers = array_fill(0, $wordGapsLength, 0);
    foreach ($numberPositionsInPattern as $indx=>$number) {
        $gapIndexInWord = $indx - 1 + $found;
        $matchedNumbers[$gapIndexInWord] = $number;
    }
    return $matchedNumbers;
}

function addMatchedNumbers(array $currentNumbers, array $numbersToAdd): array {
    foreach($currentNumbers as $index=>$number) {
        $newNumbers[$index] = ($numbersToAdd[$index] > $number) ? $numbersToAdd[$index] : $number;
    }
    return $newNumbers;
}
function isOdd(int $number): bool {
    if ($number%2 === 1) {
        return true;
    }
    return false;
}