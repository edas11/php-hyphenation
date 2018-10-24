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
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInputBuilder;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlDatabaseProxy;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\Output\ConsoleOutput;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\ConsoleDataProviderFactory;
use Edvardas\Hyphenation\Hyphenator\Providers\HttpDataProviderFactory;
use Edvardas\Hyphenation\Hyphenator\WebHyphenator;
use Edvardas\Hyphenation\UtilityComponents\Cache\MemoryCache;
use Edvardas\Hyphenation\UtilityComponents\Config\Config;
use Edvardas\Hyphenation\UtilityComponents\Console\Console;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlDatabase;
use Edvardas\Hyphenation\UtilityComponents\File\FileReader;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;
use Edvardas\Hyphenation\UtilityComponents\Logger\FileLogger;
use phpDocumentor\Reflection\File;
use Psr\Log\LoggerInterface;

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
                    $this->get(HyphenationInputBuilder::class),
                    $this->get(ConsoleOutput::class),
                    $this->get(ModelFactory::class),
                    $this->get(FileLogger::class),
                    $this->get(FileReader::class),
                    $this->get(Config::class)
                );
            case HyphenationInputBuilder::class:
                return new HyphenationInputBuilder();
                break;
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
                    $this->get(HyphenationInputBuilder::class),
                    $this->get(HttpRequest::class),
                    $this->get(Router::class),
                    $this->get(WebOutput::class),
                    $this->get(ModelFactory::class),
                    $this->get(FileLogger::class)
                );
            case WebOutput::class:
                return new WebOutput();
            case HttpRequest::class:
                return new HttpRequest();
            case Router::class:
                $routeConfigData = require 'routes.php';
                return new Router($this->get(HttpRequest::class), $routeConfigData);
            case ModelFactory::class:
                return new ModelFactory(
                    $this->get(MySqlDatabase::class),
                    $this->get(Config::class),
                    $this->get(FileReader::class),
                    $this->get(FileLogger::class)
                );
            case MySqlDatabase::class:
                $config = $this->get(Config::class);
                $host = $config->get(['mysql', 'host']);
                $port = $config->get(['mysql', 'port']);
                $db = $config->get(['mysql', 'db']);
                $user = $config->get(['mysql', 'user']);
                $pass = $config->get(['mysql', 'password']);
                $charset = $config->get(['mysql', 'charset']);
                return new MySqlDatabaseProxy($host, $port, $db, $user, $pass, $charset);
            case MemoryCache::class:
                return new MemoryCache();
            case FileLogger::class:
                return new FileLogger();
            case FileReader::class:
                return new FileReader();
            default:
                throw new \Exception("Cant create $instanceName");
        }
    }
}