<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.1
 * Time: 15.03
 */
require('algorithm.php');
$startTime = microtime(true);

$patterns = file('patterns', FILE_IGNORE_NEW_LINES);
if($patterns === false) {
    echo "Could not read patterns file.\n";
    exit;
}

if (count($argv)>1) {
    for ($i=1; $i<count($argv); $i++){
        $inputWords[$i-1] = $argv[$i];
    }
} else {
    echo "Reading word from words.txt file.\n";
    $inputWords = file('words.txt', FILE_IGNORE_NEW_LINES);
    if($inputWords === false) {
        echo "Could not read words.txt file.\n";
        exit;
    }
}

$result = [];
foreach($inputWords as $inputWord) {
    array_push($result, (new HyphenationAlgorithm())->execute($patterns, $inputWord) );
}
var_dump($result);
$endTime = microtime(true);
echo "Finished in " . ($endTime - $startTime) . " seconds.\n";