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
use Edvardas\Hyphenation\Hyphenator\DiContainer\DiContainer;
use Edvardas\Hyphenation\Hyphenator\Hyphenator;
use Edvardas\Hyphenation\Hyphenator\WebHyphenator;
use Edvardas\Hyphenation\UtilityComponents\Logger\FileLogger;
use Edvardas\Hyphenation\UtilityComponents\Cache\MemoryCache;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;

class App
{
    public const WORDS_THRESHOLD = 100000;
    public static $logger;
    public static $cache;

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
            $this->hyphenator = $container->get(ConsoleHyphenator::class);
        } else {
            $this->hyphenator = $container->get(WebHyphenator::class);
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
}
