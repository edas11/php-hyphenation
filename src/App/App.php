<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 09.36
 */

namespace Edvardas\Hyphenation\App;

use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Hyphenator;
use Edvardas\Hyphenation\UtilityComponents\Input\ConsoleInput;
use Edvardas\Hyphenation\UtilityComponents\Timer\Timer;
use Edvardas\Hyphenation\UtilityComponents\Output\ConsoleOutput;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;
use Edvardas\Hyphenation\UtilityComponents\Logger\ConsoleLogger;
use Edvardas\Hyphenation\UtilityComponents\Logger\FileLogger;
use Edvardas\Hyphenation\UtilityComponents\Cache\MemoryCache;

class App
{
    public const WORDS_THRESHOLD = 100000;
    public static $logger;
    public static $cache;
    private $timer;
    private $output;
    private $input;
    private $config;

    public function __construct()
    {
        self::$logger = new FileLogger();
        self::$cache = new MemoryCache();

        $this->config = AppConfigReader::read('config.php');
        $this->timer = new Timer();
        $this->output = new ConsoleOutput();
        $this->input = new ConsoleInput();
    }

    public function executeCommand()
    {
        $this->timer->start();
        self::$logger->info("Started hyphenation algorithm at " . date('Y-m-d H:i:s'));

        $this->hyphenator = new Hyphenator($this->config);
        $hyphenatedWords = $this->hyphenator->hyphenateWords();

        if ($hyphenatedWords)
        $this->printResult($hyphenatedWords);
        $this->printTime();
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
