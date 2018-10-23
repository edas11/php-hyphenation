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
use Edvardas\Hyphenation\Hyphenator\Model\HyphenatedWords;
use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Timer\Timer;

class WordsHyphenationWithDbAction implements Action
{
    private $output;
    private $timer;
    private $modelFactory;
    private $logger;
    private $inputWords;
    private $algorithm;

    public function __construct(HyphenationDataProvider $dataProvider, BufferedOutput $output)
    {
        $this->timer = new Timer();
        $this->output = $output;
        $this->modelFactory = $dataProvider->getModelFactory();
        $this->logger = $dataProvider->getLogger();
        $this->inputWords = $dataProvider->getWordsInput();
        $this->algorithm = $dataProvider->getAlgorithm();
    }

    public function execute(): void
    {
        $this->timer->start();

        $dbWordsModel = $this->getKnownWords();
        $hyphenatedWords = $this->hyphenateUnknownWords($dbWordsModel);
        $matchedPatternsResult = $this->getMatchedPatterns();

        $this->output->set('matchedPatterns', $matchedPatternsResult);
        $this->output->set('hyphenatedWords', $hyphenatedWords);
        $this->output->set('skippedWords', $dbWordsModel->getHyphenatedWords());

        $this->printTime();
    }

    private function getKnownWords(): HyphenatedWords
    {
        $dbWordsModel = $this->modelFactory->getKnownHyphenatedWords($this->inputWords);
        return $dbWordsModel;
    }

    private function hyphenateUnknownWords(HyphenatedWords $dbWordsModel): array
    {
        $wordsNotInDb = $dbWordsModel->filterUnknownWords($this->inputWords);
        if (count($wordsNotInDb) > 0) {
            $runner = new AlgorithmRunner($this->algorithm);
            $runner->runAndSavePatterns($wordsNotInDb);
            $hyphenatedWords = $runner->getHyphenatedWords();
            $this->saveHyphenationResults($hyphenatedWords, $runner->getMatchedPatterns());
        } else {
            $hyphenatedWords = [];
        }
        return $hyphenatedWords;
    }

    private function saveHyphenationResults(array $hyphenatedWords, array $matchedPatterns): void
    {
        $hyphenatedWordsModel = $this->modelFactory->createHyphenatedWords($hyphenatedWords);
        $wordPatternsModel = $this->modelFactory->createWordPatterns($matchedPatterns);
        $this->modelFactory->createCompositeModel([$hyphenatedWordsModel, $wordPatternsModel])->persist();
    }

    private function getMatchedPatterns(): array
    {
        $matchedPatternsResult = $this->modelFactory->getKnownWordPatterns($this->inputWords)->getMatchedPatterns();
        return $matchedPatternsResult;
    }

    private function printTime(): void
    {
        $time = $this->timer->getInterval();
        $this->output->set('time', $time);
        $this->logger->info("Finished in $time seconds.");
    }
}