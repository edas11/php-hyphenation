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
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Timer\Timer;

class ComplexHyphenateWordsAction implements Action
{
    private $output;
    private $dataProvider;
    private $timer;
    private $modelFactory;
    private $logger;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->timer = new Timer();
        $this->output = $dataProvider->getOutput();
        $this->dataProvider = $dataProvider;
        $this->modelFactory = $this->dataProvider->getModelFactory();
        $this->logger = $dataProvider->getLogger();
    }

    public function execute(): void
    {
        $inputWords = $this->dataProvider->getWordsInput();
        $algorithm = $this->dataProvider->getAlgorithm();

        $this->timer->start();

        $dbWordsModel = $this->modelFactory->getKnownHyphenatedWords($inputWords);
        $wordsNotInDb = $dbWordsModel->filterUnknownWords($inputWords);

        $resultWords = [];
        if (count($wordsNotInDb) > 0) {
            $runner = new AlgorithmRunner($algorithm);
            $resultWords = $this->hyphenateWordsNotInDb($runner, $wordsNotInDb);
        }

        $matchedPatternsResult = $this->getMatchedPatterns($inputWords);
        $this->output->printMatchedPatterns($matchedPatternsResult);
        $this->output->printHyphenatedWords($resultWords, $dbWordsModel->getWords());
        $this->printTime();
    }

    private function hyphenateWordsNotInDb($runner, $wordsNotInDb)
    {
        $runner->run($wordsNotInDb, true);
        $resultWords = $runner->getHyphenatedWords();
        $hyphenatedWordsModel = $this->modelFactory->createHyphenatedWords($resultWords);
        $wordPatterns = $this->modelFactory->createWordPatterns($runner->getMatchedPatterns());
        $this->modelFactory->createCompositeModel([$hyphenatedWordsModel, $wordPatterns])->persist();
        return $resultWords;
    }

    protected function getMatchedPatterns($inputWords)
    {
        $matchedPatternsResult = $this->modelFactory->getKnownWordPatterns($inputWords)->getMatchedPatterns();
        return $matchedPatternsResult;
    }

    private function printTime(): void
    {
        $time = $this->timer->getInterval();
        $this->output->printTime($time);
        $this->logger->info("Finished in $time seconds.");
    }
}