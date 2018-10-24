<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.24
 * Time: 09.44
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Controller\ConsoleControllers;

use Edvardas\Hyphenation\Hyphenator\Console\InputDialog;
use Edvardas\Hyphenation\Hyphenator\Controller\Controller;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\ModelAction\PatternsSaveInDbModelAction;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInputBuilder;
use Edvardas\Hyphenation\Hyphenator\Output\ConsoleOutput;
use Edvardas\Hyphenation\UtilityComponents\Config\Config;
use Edvardas\Hyphenation\UtilityComponents\File\FileReader;
use Psr\Log\LoggerInterface;

class PatternsTransferToDbController implements Controller
{
    private $modelInputBuilder;
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
        $this->modelInputBuilder = $modelInputBuilder;
        $this->output = $output;
        $this->modelFactory = $modelFactory;
        $this->logger = $logger;
        $this->fileReader = $fileReader;
        $this->config = $config;
    }

    public function handleRequest(): void
    {
        $patternsFileName = $this->config->get(['patternsFileName'], 'patterns');
        $patterns = $this->fileReader->read($patternsFileName, $this->logger);
        $this->modelInputBuilder->setPatternsInput($patterns);
        $modelInput = $this->modelInputBuilder->build();
        $action = new PatternsSaveInDbModelAction($modelInput, $this->output, $this->modelFactory, $this->logger);
        $action->execute();
    }
}