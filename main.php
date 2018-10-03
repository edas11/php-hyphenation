<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.1
 * Time: 15.03
 */
use Edvardas\Hyphenation\Timer\Timer;
use Edvardas\Hyphenation\HyphenationAlgorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\HyphenationAlgorithm\ShortTreeHyphenationAlgorithm;

spl_autoload_register(function ($class_name) {
    $fileName = str_replace('Edvardas\Hyphenation', './src', $class_name);
    $fileName = str_replace('\\', '/', $fileName);
    include $fileName . '.php';
});

$timer = new Timer();
$timer->start();

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
$hyphAlgorithm = new FullTreeHyphenationAlgorithm($patterns);
foreach($inputWords as $inputWord) {
    //array_push($result, $hyphAlgorithm->execute($inputWord) );
    var_dump($hyphAlgorithm->execute($inputWord));
}
//var_dump($result);
echo "Finished in " . $timer->getInterval() . " seconds.\n";