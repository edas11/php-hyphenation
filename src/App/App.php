<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 09.36
 */

namespace Edvardas\Hyphenation\App;

use Edvardas\Hyphenation\HyphenationAlgorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\UtilityComponents\Input\ConsoleInput;
use Edvardas\Hyphenation\UtilityComponents\Timer\Timer;
use Edvardas\Hyphenation\UtilityComponents\Output\ConsoleOutput;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;
use Edvardas\Hyphenation\UtilityComponents\Logger\ConsoleLogger;
use Edvardas\Hyphenation\UtilityComponents\Logger\FileLogger;
use Edvardas\Hyphenation\UtilityComponents\Cache\MemoryCache;

class App
{
    public static $logger;

    public static $cache;

    private $timer;

    private $output;

    private $input;

    private const WORDS_THRESHOLD = 100000;

    public function __construct()
    {
        $this->registerProjectSrcAutoload();

        self::$logger = new FileLogger();
        self::$cache = new MemoryCache();

        $this->timer = new Timer();
        $this->output = new ConsoleOutput();
        $this->input = new ConsoleInput();
    }

    public function executeCommand()
    {
        $this->timer->start();

        self::$logger->info("Started hyphenation algorithm at " . date('Y-m-d H:i:s'));

        $patterns = $this->loadPatterns();

        $inputWords = $this->input->getWords();

        $this->turnOffLoggerIfMoreWordsThanThreshold($inputWords);

        $algorithm = $this->input->getAlgorithm($patterns);

        $hyphenatedWords = $this->hyphenateWords($inputWords, $algorithm);

        $this->printResult($hyphenatedWords);

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

    public function turnOffLoggerIfMoreWordsThanThreshold(array $inputWords): void
    {
        if (count($inputWords) > self::WORDS_THRESHOLD) {
            self::$logger->notice('Too many words, disabling logger.');
            self::$logger = new NullLogger();
        }
    }

    public function hyphenateWords(array $inputWords, HyphenationAlgorithmInterface $hyphAlgorithm): array
    {
        $result = [];
        foreach ($inputWords as $inputWord) {
            array_push($result, $hyphAlgorithm->execute($inputWord));
        }
        return $result;
    }

    public function printResult($hyphenatedWords)
    {
        $this->output->printResult($hyphenatedWords);
    }

    public function printTime(): void
    {
        $time = $this->timer->getInterval();
        $this->output->printTime($time);
        self::$logger->info("Finished in $time seconds.");
    }

}
