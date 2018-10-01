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
    $matchedNumbers = [];
    while( ($found = stripos($inputWord, $reducedPattern, $found+1)) !== false) {
        echo $pattern."\n";
        if ($pattern[0] === '.' && $found !== 0) break;
        if ($pattern[strlen($pattern)-1] === '.' && $found !== ( strlen($inputWord) - strlen($reducedPattern))) break;
        $matchedNumbers = array_merge($matchedNumbers, numbersOfOneMatch($found, $numberPositionsInPattern));
    }
    if (count($matchedNumbers) > 0)  array_push($matchedNumbersAll, $matchedNumbers);
}

var_dump($matchedNumbersAll);

// pattern index => pattern number
function numberPositionsInPattern(string $pattern): array {
    $numberPos = [];
    for ( $i = 0; $i < strlen($pattern ); $i++){
        if (is_numeric($pattern[$i])) {
            $numberPos[$i] = $pattern[$i];
        };
    }
    return $numberPos;
}

// word index => pattern number
function numbersOfOneMatch($found, $numberPositionsInPattern): array {
    foreach ($numberPositionsInPattern as $indx=>$number) {
        $numberIndexInWord = $indx + $found;
        $matchedNumbers[$numberIndexInWord] = $number;
    }
    return $matchedNumbers;
}