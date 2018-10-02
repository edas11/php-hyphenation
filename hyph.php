<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.1
 * Time: 15.03
 */

$patterns = file('patterns', FILE_IGNORE_NEW_LINES);
if($patterns === false) {
    echo "Could not read patterns file.\n";
    exit;
}

if (count($argv)>1) {
    $inputWord= $argv[1];
} else {
    echo "Not enough input arguments.\n";
    exit;
}

$matchedNumbersAll = [];
$reduceChar = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '.'];
foreach($patterns as $pattern) {
    $reducedPattern = str_replace($reduceChar, '', $pattern);
    $numberPositionsInPattern = numberPositionsInPattern($pattern);


    $found = -1;
    $matchedNumbers = array_fill(0, strlen($inputWord)-1, 0);
    while( ($found = stripos($inputWord, $reducedPattern, $found+1)) !== false) {
        //echo $pattern."\n";
        if ($pattern[0] === '.' && $found !== 0) {
            break;
        }
        if (
            $pattern[strlen($pattern)-1] === '.'
            && $found !== ( strlen($inputWord) - strlen($reducedPattern))
        ) {
            break;
        }

        $matchedNumbers = addMatchedNumbers(
            $matchedNumbers,
            numbersOfOneMatch($found, $numberPositionsInPattern, strlen($inputWord)-1)
        );
    }
    if (array_sum($matchedNumbers) > 0)  array_push($matchedNumbersAll, $matchedNumbers);
}

//var_dump($matchedNumbersAll);
$numberInWord = array_fill(0, strlen($inputWord)-1, 0);
foreach($matchedNumbersAll as $matchedNumbers) {
    $numberInWord = addMatchedNumbers($numberInWord, $matchedNumbers);
}
var_dump($numberInWord);
$result = $inputWord;
$dashesNumber = 0;
foreach ( $numberInWord as $index=>$number ) {
    $cutPoint = $index + $dashesNumber;
    if ( isOdd($number) ) {
        $result = substr($result, 0, $cutPoint+1).'-'.substr($result, $cutPoint+1);
        $dashesNumber = $dashesNumber + 1;
    }
}
echo $result."\n";

// pattern gap index => pattern number
function numberPositionsInPattern(string $pattern): array {
    $patternNoPoint = str_replace('.', '', $pattern);
    $numberIndexAddition = 0;
    if ( !is_numeric($patternNoPoint[0]) ) {
        $numberIndexAddition = 1;
    }

    $patternExpaned = '';
    for ( $i = 0; $i < strlen($patternNoPoint); $i++) {
        if (is_numeric($patternNoPoint[$i])){
            $patternExpaned = $patternExpaned.$patternNoPoint[$i];
        } elseif (!is_numeric($patternNoPoint[$i+1])) {
            $patternExpaned = $patternExpaned.$patternNoPoint[$i].' ';
        } else {
            $patternExpaned = $patternExpaned.$patternNoPoint[$i];
        }
    }

    $numberPos = [];
    for ( $i = 0; $i < strlen($patternExpaned); $i++){
        if (is_numeric($patternExpaned[$i])) {
            $numberPos[($i + $numberIndexAddition)/2] = $patternExpaned[$i];
        };
    }
    if ($pattern==='.mis1') var_dump($numberPos);
    return $numberPos;
}

// word gap index => pattern number
function numbersOfOneMatch($found, $numberPositionsInPattern, $wordGapsLength): array {
//    var_dump($found, $numberPositionsInPattern);
    $matchedNumbers = array_fill(0, $wordGapsLength, 0);
    foreach ($numberPositionsInPattern as $indx=>$number) {
        $numberIndexInWord = $indx + $found - 1;
        $matchedNumbers[$numberIndexInWord] = $number;
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