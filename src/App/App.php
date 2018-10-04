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
use Edvardas\Hyphenation\Logger\NullLogger;
use Edvardas\Hyphenation\Logger\ConsoleLogger;
use Edvardas\Hyphenation\Logger\FileLogger;
use Edvardas\Hyphenation\Cache\MemoryCache;

class App
{
    public static $logger;

    public static $cache;

    private $timer;

    private $output;

    public function __construct()
    {
        $this->registerProjectSrcAutoload();

        self::$logger = new FileLogger();
        self::$cache = new MemoryCache();

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

    public function registerProjectSrcAutoload()
    {
        spl_autoload_register(
            function ($class_name) {
                $fileName = str_replace('Edvardas\Hyphenation', './src', $class_name);
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
            self::$logger = new NullLogger();
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
        $this->output->printResult($result);
    }

    public function printTime(): void
    {
        $time = $this->timer->getInterval();
        $this->output->printTime($time);
        self::$logger->info("Finished in $time seconds.");
    }

}
