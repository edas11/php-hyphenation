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
use Edvardas\Hyphenation\Logger\ConsoleLogger;
use Edvardas\Hyphenation\Logger\FileLogger;

class App
{
    public static $logger;

    private $timer;

    private $output;

    public function __construct()
    {
        $this->registerAutoload();

        self::$logger = new FileLogger();

        $this->timer = new Timer();
        $this->output = new ConsoleOutput();
    }

    public function executeCommand()
    {
        $this->timer->start();

        self::$logger->info("Started hyphenation algorithm at " . date('Y-m-d H:i:s'));

        $patterns = $this->loadPatterns();

        $inputWords = $this->loadInputWords();

        $result = $this->hyphenateWords($inputWords, $patterns);

        $this->printResult($result);

        $this->printTime();
    }

    public function registerAutoload()
    {
        spl_autoload_register(
            function ($class_name) {
                if (strpos($class_name, 'Edvardas\Hyphenation') === 0) {
                    $fileName = str_replace('Edvardas\Hyphenation', './src', $class_name);
                } else {
                    $fileName = "./vendor/$class_name";
                }
                $fileName = str_replace('\\', '/', $fileName);
                include $fileName . '.php';
            }
        );
    }

    public function loadPatterns(): array
    {
        $patterns = file('patterns', FILE_IGNORE_NEW_LINES);
        if ($patterns === false) {
            self::$logger->error("Could not read patterns file.");
            exit;
        }
        return $patterns;
    }

    public function loadInputWords(): array
    {
        global $argv;
        if (count($argv) > 1) {
            for ($i=1; $i<count($argv); $i++){
                $inputWords[$i-1] = $argv[$i];
            }
        } else {
            self::$logger->info("Reading words from words.txt file.");
            $inputWords = file('words.txt', FILE_IGNORE_NEW_LINES);
            if($inputWords === false) {
                self::$logger->error("Could not read words.txt file.");
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
        self::$logger->info("Finished in " . $this->timer->getInterval() . " seconds.");
    }

}
