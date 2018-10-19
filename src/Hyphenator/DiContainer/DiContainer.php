<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.15
 * Time: 16.38
 */

namespace Edvardas\Hyphenation\Hyphenator\DiContainer;

use Edvardas\Hyphenation\Hyphenator\Console\InputDialog;
use Edvardas\Hyphenation\Hyphenator\ConsoleHyphenator;
use Edvardas\Hyphenation\Hyphenator\Controller\ConsoleController;
use Edvardas\Hyphenation\Hyphenator\Controller\HttpController;
use Edvardas\Hyphenation\Hyphenator\Database\MySqlDatabaseProxy;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\Output\ConsoleOutput;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationConsoleDataProvider;
use Edvardas\Hyphenation\Hyphenator\Providers\ConsoleDataProviderFactory;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationHttpDataProvider;
use Edvardas\Hyphenation\Hyphenator\Providers\HttpDataProviderFactory;
use Edvardas\Hyphenation\Hyphenator\WebHyphenator;
use Edvardas\Hyphenation\UtilityComponents\Cache\MemoryCache;
use Edvardas\Hyphenation\UtilityComponents\Config\Config;
use Edvardas\Hyphenation\UtilityComponents\Console\Console;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlDatabase;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;
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
                return new ConsoleHyphenator($this->get(ConsoleController::class), $this->get(ConsoleOutput::class));
            case ConsoleController::class:
                return new ConsoleController(
                    $this->get(InputDialog::class),
                    $this->get(ConsoleDataProviderFactory::class),
                    $this->get(ConsoleOutput::class)
                );
            case ConsoleDataProviderFactory::class:
                return new ConsoleDataProviderFactory(
                    $this->get(InputDialog::class),
                    $this->get(Config::class),
                    $this->get(ModelFactory::class),
                    $this->get(MemoryCache::class),
                    $this->get(FileLogger::class)
                );
            case ConsoleOutput::class:
                return new ConsoleOutput();
            case InputDialog::class:
                return new InputDialog($this->get(Console::class));
            case Console::class:
                return new Console();
            case Config::class:
                $configData = require('config.php');
                return new Config($configData);
            case WebHyphenator::class:
                return new WebHyphenator(
                    $this->get(HttpController::class),
                    $this->get(WebOutput::class)
                );
            case HttpController::class:
                return new HttpController(
                    $this->get(HttpDataProviderFactory::class),
                    $this->get(HttpRequest::class),
                    $this->get(Router::class),
                    $this->get(WebOutput::class)
                );
            case WebOutput::class:
                return new WebOutput();
            case HttpDataProviderFactory::class:
                return new HttpDataProviderFactory(
                    $this->get(ModelFactory::class),
                    $this->get(MemoryCache::class),
                    $this->get(FileLogger::class)
                );
            case HttpRequest::class:
                return new HttpRequest();
            case Router::class:
                return new Router($this->get(HttpRequest::class));
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