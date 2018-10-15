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
use Edvardas\Hyphenation\Hyphenator\Controller\HttpController;
use Edvardas\Hyphenation\Hyphenator\Input\ConsoleInput;
use Edvardas\Hyphenation\Hyphenator\Output\ConsoleOutput;
use Edvardas\Hyphenation\Hyphenator\Output\JsonHyphenationOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationConsoleDataProvider;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationHttpDataProvider;
use Edvardas\Hyphenation\Hyphenator\WebHyphenator;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Route;

class DiContainer
{
    private $instances = [];

    public function getConsoleHyphenator(): ConsoleHyphenator
    {
        $instanceName = 'ConsoleHyphenator';
        if (!array_key_exists($instanceName, $this->instances)) {
            $this->instances[$instanceName] = new ConsoleHyphenator($this->getConsoleController());
        }
        return $this->instances[$instanceName];
    }

    public function getConsoleController(): ConsoleController
    {
        $instanceName = 'ConsoleController';
        if (!array_key_exists($instanceName, $this->instances)) {
            $this->instances[$instanceName] = new ConsoleController($this->getConsoleInput(), $this->getHyphenationConsoleDataProvider());
        }
        return $this->instances[$instanceName];
    }

    public function getHyphenationConsoleDataProvider(): HyphenationConsoleDataProvider
    {
        $instanceName = 'HyphenationConsoleDataProvider';
        if (!array_key_exists($instanceName, $this->instances)) {
            $this->instances[$instanceName] = new HyphenationConsoleDataProvider($this->getConsoleInput(), $this->getConsoleOutput());
        }
        return $this->instances[$instanceName];
    }

    public function getConsoleOutput(): ConsoleOutput
    {
        $instanceName = 'ConsoleOutput';
        if (!array_key_exists($instanceName, $this->instances)) {
            $this->instances[$instanceName] = new ConsoleOutput();
        }
        return $this->instances[$instanceName];
    }

    public function getConsoleInput(): ConsoleInput
    {
        $instanceName = 'ConsoleInput';
        if (!array_key_exists($instanceName, $this->instances)) {
            $this->instances[$instanceName] = new ConsoleInput();
        }
        return $this->instances[$instanceName];
    }

    public function getWebHyphenator(): WebHyphenator
    {
        $instanceName = 'WebHyphenator';
        if (!array_key_exists($instanceName, $this->instances)) {
            $this->instances[$instanceName] = new WebHyphenator($this->getHttpController(), $this->getJsonHyphenationOutput());
        }
        return $this->instances[$instanceName];
    }

    public function getHttpController(): HttpController
    {
        $instanceName = 'HttpController';
        if (!array_key_exists($instanceName, $this->instances)) {
            $this->instances[$instanceName] = new HttpController($this->getHyphenationHttpDataProvider(), $this->getRoute());
        }
        return $this->instances[$instanceName];
    }

    public function getJsonHyphenationOutput(): JsonHyphenationOutput
    {
        $instanceName = 'JsonHyphenationOutput';
        if (!array_key_exists($instanceName, $this->instances)) {
            $this->instances[$instanceName] = new JsonHyphenationOutput();
        }
        return $this->instances[$instanceName];
    }

    public function getHyphenationHttpDataProvider(): HyphenationHttpDataProvider
    {
        $instanceName = 'HyphenationHttpDataProvider';
        if (!array_key_exists($instanceName, $this->instances)) {
            $this->instances[$instanceName] = new HyphenationHttpDataProvider($this->getJsonHyphenationOutput());
        }
        return $this->instances[$instanceName];
    }

    public function getRoute(): Route
    {
        return HttpRequest::getRoute();
    }
}