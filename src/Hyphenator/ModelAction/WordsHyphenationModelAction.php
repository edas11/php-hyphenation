<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.25
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\ModelAction;

use Edvardas\Hyphenation\Hyphenator\Algorithm\AlgorithmRunner;
use Edvardas\Hyphenation\Hyphenator\File\PatternsFile;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Timer\Timer;
use Psr\Log\LoggerInterface;

class WordsHyphenationModelAction implements ModelAction
{
    private $output;
    private $timer;
    private $logger;
    private $inputWords;
    private $algorithmName;
    private $modelFactory;

    public function __construct(
        HyphenationInput $modelInput,
        BufferedOutput $output,
        ModelFactory $modelFactory,
        LoggerInterface $logger
    ) {
        $this->timer = new Timer();
        $this->output = $output;
        $this->logger = $logger;
        $this->inputWords = $modelInput->getWordsInput();
        $this->algorithmName = $modelInput->getAlgorithmName();
        $this->modelFactory = $modelFactory;
    }

    public function execute(): void
    {
        $this->timer->start();

        $patterns = $this->modelFactory->getKnownPatternsFromFile();
        $algorithm = new $this->algorithmName($patterns, $this->logger);
        $runner = new AlgorithmRunner($algorithm);
        $runner->run($this->inputWords);
        $this->output->set('result', $runner->getHyphenatedWords());

        $this->printTime();
    }

    public function printTime(): void
    {
        $time = $this->timer->getInterval();
        $this->output->set('time', $time);
        $this->logger->info("Finished in $time seconds.");
    }
}