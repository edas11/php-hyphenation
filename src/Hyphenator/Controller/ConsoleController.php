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
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;
use Psr\Log\LoggerInterface;

class ConsoleController implements Controller
{
    private $modelInputBuilder;
    private $consoleInputData;
    private $output;
    private $modelFactory;
    private $logger;
    private $fileReader;
    private $config;

    public function __construct(
        InputDialog $input,
        HyphenationInputBuilder $modelInputBuilder,
        ConsoleOutput $output,
        ModelFactory $modelFactory,
        LoggerInterface $logger,
        FileReader $fileReader,
        Config $config
    ) {
        $this->consoleInputData = $input->getConsoleInput();
        $this->modelInputBuilder = $modelInputBuilder;
        $this->output = $output;
        $this->modelFactory = $modelFactory;
        $this->logger = $logger;
        $this->fileReader = $fileReader;
        $this->config = $config;
    }

    public function handleRequest(): void
    {
        $this->setWordsInput();
        $this->setPatternsInput();
        $this->setAlgorithmName();
        $modelInput = $this->modelInputBuilder->build();

        $actionName = $this->consoleInputData->getActionName();
        if (class_exists($actionName)) {
            $action = new $actionName($modelInput, $this->output, $this->modelFactory, $this->logger);
            $action->execute();
        }
    }

    private function setWordsInput(): void
    {
        $wordsInput = [];
        if ($this->consoleInputData->isWordsFromFile()) {
            $fileName = $this->config->get(['wordsFileName'], 'words.txt');
            $this->logger->info("Reading words from words.txt file.");
            $wordsInput = $this->fileReader->read('words.txt', $this->logger);
        } elseif ($this->consoleInputData->isWordsFromInput()) {
            $wordsInput = $this->consoleInputData->getWords();
        }
        if (count($wordsInput) > (int)$this->config->get(['wordsThreshold'])) {
            $this->logger->notice('Too many words, disabling logger.');
            $this->logger = new NullLogger();
        }
        $this->modelInputBuilder->setWordsInput($wordsInput);
    }

    private function setAlgorithmName(): void
    {
        $algorithmName = $this->consoleInputData->getAlgorithmName();
        $this->modelInputBuilder->setAlgorithmName($algorithmName);
    }

    private function setPatternsInput(): void
    {
        $patterns = [];
        if ($this->consoleInputData->isPatternsFromDb()) {
            $patternsFileName = $this->config->get(['patternsFileName'], 'patterns');
            $patterns = $this->fileReader->read($patternsFileName, $this->logger);
        }
        $this->modelInputBuilder->setPatternsInput($patterns);
    }
}