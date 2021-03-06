<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 16.41
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Controller;

use Edvardas\Hyphenation\Hyphenator\File\PatternsFile;
use Edvardas\Hyphenation\Hyphenator\File\WordsFile;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\Console\InputDialog;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInputBuilder;
use Edvardas\Hyphenation\Hyphenator\Output\ConsoleOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationConsoleDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Config\Config;
use Edvardas\Hyphenation\UtilityComponents\File\FileReader;
use Psr\Log\LoggerInterface;

class ConsoleController implements Controller
{
    private $modelInputBuilder;
    private $inputDialog;
    private $output;
    private $modelFactory;
    private $logger;
    private $fileReader;
    private $config;

    public function __construct(
        InputDialog $inputDialog,
        HyphenationInputBuilder $modelInputBuilder,
        ConsoleOutput $output,
        ModelFactory $modelFactory,
        LoggerInterface $logger,
        FileReader $fileReader,
        Config $config
    ) {
        $this->inputDialog = $inputDialog;
        $this->modelInputBuilder = $modelInputBuilder;
        $this->output = $output;
        $this->modelFactory = $modelFactory;
        $this->logger = $logger;
        $this->fileReader = $fileReader;
        $this->config = $config;
    }

    public function handleRequest(): void
    {
        $handlerControllerName = $this->inputDialog->getHandlerName();
        if (class_exists($handlerControllerName)) {
            $action = new $handlerControllerName(
                $this->inputDialog,
                $this->modelInputBuilder,
                $this->output,
                $this->modelFactory,
                $this->logger,
                $this->fileReader,
                $this->config
            );
            $action->handleRequest();
        } else {
            throw new \Exception("No handler $handlerControllerName");
        }
    }
}