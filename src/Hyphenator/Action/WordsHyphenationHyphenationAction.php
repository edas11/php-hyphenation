<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.25
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\Hyphenator\Algorithm\AlgorithmRunner;
use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Timer\Timer;

class WordsHyphenationHyphenationAction implements HyphenationAction
{
    private $output;
    private $timer;
    private $logger;
    private $inputWords;
    private $algorithm;

    public function __construct(HyphenationDataProvider $dataProvider, BufferedOutput $output)
    {
        $this->timer = new Timer();
        $this->output = $output;
        $this->logger = $dataProvider->getLogger();
        $this->inputWords = $dataProvider->getWordsInput();
        $this->algorithm = $dataProvider->getAlgorithm();
    }

    public function execute(): void
    {
        $this->timer->start();

        $runner = new AlgorithmRunner($this->algorithm);
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