<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.25
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Algorithm\AlgorithmRunner;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Timer\Timer;
use Psr\Log\LoggerInterface;

class SimpleHyphenateWordsAction implements Action
{
    private $output;
    private $dataProvider;
    private $timer;
    private $logger;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->timer = new Timer();
        $this->output = $dataProvider->getOutput();
        $this->dataProvider = $dataProvider;
        $this->logger = $dataProvider->getLogger();
    }

    public function execute()
    {
        $inputWords = $this->dataProvider->getWords();
        $patterns = $this->dataProvider->getPatterns()->getPatterns();
        $algorithm = $this->dataProvider->getAlgorithm($patterns);
        $runner = new AlgorithmRunner($algorithm);

        $this->timer->start();

        $runner->run($inputWords);
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