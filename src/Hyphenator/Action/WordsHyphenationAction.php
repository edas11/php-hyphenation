<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.25
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\Hyphenator\Algorithm\AlgorithmRunner;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Timer\Timer;

class WordsHyphenationAction implements Action
{
    private $output;
    private $timer;
    private $logger;
    private $inputWords;
    private $algorithm;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->timer = new Timer();
        $this->output = $dataProvider->getOutput();
        $this->logger = $dataProvider->getLogger();
        $this->inputWords = $dataProvider->getWordsInput();
        $this->algorithm = $dataProvider->getAlgorithm();
    }

    public function execute(): void
    {
        $this->timer->start();

        $runner = new AlgorithmRunner($this->algorithm);
        $runner->run($this->inputWords);
        $this->output->printResult($runner->getHyphenatedWords());

        $this->printTime();
    }

    public function printTime(): void
    {
        $time = $this->timer->getInterval();
        $this->output->printTime($time);
        $this->logger->info("Finished in $time seconds.");
    }
}