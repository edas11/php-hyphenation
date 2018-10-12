<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 09.36
 */

namespace Edvardas\Hyphenation\App;

use Edvardas\Hyphenation\Hyphenator\ConsoleHyphenator;
use Edvardas\Hyphenation\Hyphenator\WebHyphenator;
use Edvardas\Hyphenation\UtilityComponents\Config\Config;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlDatabase;
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

    public function __construct()
    {
        self::$logger = new FileLogger();
        self::$cache = new MemoryCache();
    }

    public function executeCommand()
    {
        self::$logger->info("Started hyphenation algorithm at " . date('Y-m-d H:i:s'));
        if (php_sapi_name() === 'cli') {
            $this->hyphenator = new ConsoleHyphenator();
        } else {
            $this->hyphenator = new WebHyphenator();
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

    public static function getDb(): MySqlDatabase
    {
        if (!isset(self::$db)) {
            $host = App::getConfig()->get(['mysql', 'host']);
            $db = App::getConfig()->get(['mysql', 'db']);
            $user = App::getConfig()->get(['mysql', 'user']);
            $pass = App::getConfig()->get(['mysql', 'password']);
            $charset = App::getConfig()->get(['mysql', 'charset']);
            self::$db = new MySqlDatabase($host, $db, $user, $pass, $charset);
        }
        return self::$db;
    }

}
