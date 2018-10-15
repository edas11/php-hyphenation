<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 09.36
 */

namespace Edvardas\Hyphenation\App;

use Edvardas\Hyphenation\Hyphenator\DiContainer\DiContainer;
use Edvardas\Hyphenation\Hyphenator\Database\MySqlDatabaseProxy;
use Edvardas\Hyphenation\Hyphenator\Hyphenator;
use Edvardas\Hyphenation\UtilityComponents\Config\Config;
use Edvardas\Hyphenation\UtilityComponents\Database\SqlDatabase;
use Edvardas\Hyphenation\UtilityComponents\Logger\FileLogger;
use Edvardas\Hyphenation\UtilityComponents\Cache\MemoryCache;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;

class App
{
    public const WORDS_THRESHOLD = 100000;
    public static $logger;
    public static $cache;
    private static $config;
    private static $db;

    /**
     * @var Hyphenator
     */
    private $hyphenator;

    public function __construct()
    {
        self::$logger = new FileLogger();
        self::$cache = new MemoryCache();
    }

    public function executeCommand()
    {
        self::$logger->info("Started hyphenation algorithm at " . date('Y-m-d H:i:s'));
        $container = new DiContainer();
        if (php_sapi_name() === 'cli') {
            $this->hyphenator = $container->getConsoleHyphenator();
        } else {
            $this->hyphenator = $container->getWebHyphenator();
        }
        $this->hyphenator->execute();

    }

    public static function wordsReadEvent(int $numberOfWords): void
    {
        if ($numberOfWords > self::WORDS_THRESHOLD) {
            self::$logger->notice('Too many words, disabling logger.');
            self::$logger = new NullLogger();
        }
    }

    public static function getConfig(array $keys, string $default = ''): string
    {
        if (!isset(self::$config)) {
            self::$config = self::readConfig('config.php');
        }
        return self::$config->get($keys, $default);
    }

    private static function readConfig(string $pathToConfig): Config
    {
        $configData = require($pathToConfig);
        return new Config($configData);
    }

    public static function getDb(): SqlDatabase
    {
        if (!isset(self::$db)) {
            self::$db = new MySqlDatabaseProxy();
        }
        return self::$db;
    }
}
