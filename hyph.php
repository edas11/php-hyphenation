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
    echo "Not enough input arguments.\n";
    exit;
}

foreach($inputWords as $inputWord) {
    $result = hyphenationAlgorithm($patterns, $inputWord);
    echo $result . "\n";
    $endTime = microtime(true);
}
echo "Finished in " . ($endTime - $startTime) . " microseconds.\n";