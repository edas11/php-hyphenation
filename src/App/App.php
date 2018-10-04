<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 09.36
 */

namespace Edvardas\Hyphenation\App;

use Edvardas\Hyphenation\Timer\Timer;
use Edvardas\Hyphenation\Output\ConsoleOutput;
use Edvardas\Hyphenation\HyphenationAlgorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\HyphenationAlgorithm\ShortTreeHyphenationAlgorithm;

class App
{

    private $timer;
    private $output;

    public function __construct()
    {
        $this->registerAutoload();
        $this->timer = new Timer();
        $this->output = new ConsoleOutput();
    }

    public function executeCommand()
    {

        $this->timer->start();

        $patterns = $this->loadPatterns();

        $inputWords = $this->loadInputWords();

        $result = $this->hyphenateWords($inputWords, $patterns);

        $this->printResult($result);

        $this->printTime();

    }

    public function registerAutoload()
    {
        spl_autoload_register(function ($class_name) {
            $fileName = str_replace('Edvardas\Hyphenation', './src', $class_name);
            $fileName = str_replace('\\', '/', $fileName);
            include $fileName . '.php';
        });
    }

    public function loadPatterns(): array
    {
        $patterns = file('patterns', FILE_IGNORE_NEW_LINES);
        if($patterns === false) {
            $this->output->writeError("Could not read patterns file.\n");
            exit;
        }
        return $patterns;
    }

    public function loadInputWords(): array
    {
        global $argv;
        if (count($argv)>1) {
            for ($i=1; $i<count($argv); $i++){
                $inputWords[$i-1] = $argv[$i];
            }
        } else {
            $this->output->writeInfo("Reading word from words.txt file.\n");
            $inputWords = file('words.txt', FILE_IGNORE_NEW_LINES);
            if($inputWords === false) {
                $this->output->writeError("Could not read words.txt file.\n");
                exit;
            }
        }
        return $inputWords;
    }

    public function hyphenateWords(array $inputWords, array $patterns): array
    {
        $result = [];
        $hyphAlgorithm = new FullTreeHyphenationAlgorithm($patterns);
        foreach($inputWords as $inputWord) {
            array_push($result, $hyphAlgorithm->execute($inputWord) );
        }
        return $result;
    }

    public function printResult($result)
    {
        var_dump($result);
    }

    public function printTime(): void
    {
        $this->output->writeInfo("Finished in " . $this->timer->getInterval() . " seconds.\n");
    }

}
