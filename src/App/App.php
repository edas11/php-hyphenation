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
use Edvardas\Hyphenation\UtilityComponents\Config\Config;
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
    private static $config;

    public function __construct()
    {
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

        $this->hyphenator = new Hyphenator();
        $this->hyphenator->execute();

        $this->printTime();
    }

    public function printTime(): void
    {
        $time = $this->timer->getInterval();
        $this->output->printTime($time);
        self::$logger->info("Finished in $time seconds.");
    }

    public static function getConfig(): Config
    {
        if (!isset(self::$config)) {
            self::$config = self::readConfig('config.php');
        }
        return self::$config;
    }

    private static function readConfig(string $pathToConfig): Config
    {
        $configData = require($pathToConfig);
        return new Config($configData);
    }

}
