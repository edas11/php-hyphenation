<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.24
 * Time: 09.45
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Controller\ConsoleControllers;

use Edvardas\Hyphenation\Hyphenator\Console\InputDialog;
use Edvardas\Hyphenation\Hyphenator\Controller\Controller;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\ModelAction\WordsHyphenationWithDbModelAction;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInputBuilder;
use Edvardas\Hyphenation\Hyphenator\Output\ConsoleOutput;
use Edvardas\Hyphenation\UtilityComponents\Config\Config;
use Edvardas\Hyphenation\UtilityComponents\File\FileReader;
use Psr\Log\LoggerInterface;

class WordsHyphenationWithDController implements Controller
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
        $inputWords = $this->inputDialog->getInputWords();
        if (count($inputWords) === 0) {
            $wordsFileName = $this->config->get(['wordsFileName'], 'words.txt');
            $this->logger->info("Reading words from $wordsFileName file.");
            $inputWords = $this->fileReader->read($wordsFileName, $this->logger);
        }
        if (count($inputWords) > (int)$this->config->get(['wordsThreshold'])) {
            $this->logger->notice('Too many words, disabling logger.');
            $this->logger = new NullLogger();
        }
        $this->modelInputBuilder->setWordsInput($inputWords);
        $this->modelInputBuilder->setAlgorithmName($this->inputDialog->getAlgorithmName());
        $modelInput = $this->modelInputBuilder->build();
        $action = new WordsHyphenationWithDbModelAction($modelInput, $this->output, $this->modelFactory, $this->logger);
        $action->execute();
    }
}