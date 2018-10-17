<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.15
 * Time: 16.38
 */

namespace Edvardas\Hyphenation\Hyphenator\DiContainer;

use Edvardas\Hyphenation\Hyphenator\ConsoleHyphenator;
use Edvardas\Hyphenation\Hyphenator\Controller\ConsoleController;
use Edvardas\Hyphenation\Hyphenator\Controller\HttpMainController;
use Edvardas\Hyphenation\Hyphenator\Database\MySqlDatabaseProxy;
use Edvardas\Hyphenation\Hyphenator\Input\ConsoleInput;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\Output\ConsoleOutput;
use Edvardas\Hyphenation\Hyphenator\Output\JsonHyphenationOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationConsoleDataProvider;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationHttpDataProvider;
use Edvardas\Hyphenation\Hyphenator\WebHyphenator;
use Edvardas\Hyphenation\UtilityComponents\Cache\MemoryCache;
use Edvardas\Hyphenation\UtilityComponents\Config\Config;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlDatabase;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Route;
use Edvardas\Hyphenation\UtilityComponents\Logger\FileLogger;

class DiContainer
{
    private $instances = [];

    public function get(string $instanceName)
    {
        if (!array_key_exists($instanceName, $this->instances)) {
            $this->instances[$instanceName] = $this->create($instanceName);
        }
        return $this->instances[$instanceName];
    }

    private function create(string $instanceName)
    {
        switch ($instanceName) {
            case ConsoleHyphenator::class:
                return new ConsoleHyphenator($this->get(ConsoleController::class));
            case ConsoleController::class:
                return new ConsoleController(
                    $this->get(ConsoleInput::class),
                    $this->get(HyphenationConsoleDataProvider::class)
                );
            case HyphenationConsoleDataProvider::class:
                return new HyphenationConsoleDataProvider(
                    $this->get(ConsoleInput::class),
                    $this->get(ConsoleOutput::class),
                    $this->get(Config::class),
                    $this->get(ModelFactory::class),
                    $this->get(MemoryCache::class),
                    $this->get(FileLogger::class)
                );
            case ConsoleOutput::class:
                return new ConsoleOutput();
            case ConsoleInput::class:
                return new ConsoleInput();
            case Config::class:
                $configData = require('config.php');
                return new Config($configData);
            case WebHyphenator::class:
                return new WebHyphenator(
                    $this->get(HttpMainController::class),
                    $this->get(JsonHyphenationOutput::class)
                );
            case HttpMainController::class:
                return new HttpMainController(
                    $this->get(HyphenationHttpDataProvider::class),
                    $this->get(HttpRequest::class)
                );
            case JsonHyphenationOutput::class:
                return new JsonHyphenationOutput();
            case HyphenationHttpDataProvider::class:
                return new HyphenationHttpDataProvider(
                    $this->get(JsonHyphenationOutput::class),
                    $this->get(ModelFactory::class),
                    $this->get(MemoryCache::class),
                    $this->get(FileLogger::class)
                );
            case HttpRequest::class:
                return new HttpRequest();
            case ModelFactory::class:
                return new ModelFactory($this->get(MySqlDatabase::class));
            case MySqlDatabase::class:
                return new MySqlDatabaseProxy($this->get(Config::class));
            case MemoryCache::class:
                return new MemoryCache();
            case FileLogger::class:
                return new FileLogger();
            default:
                throw new \Exception("Cant create $instanceName");
        }
    }
}