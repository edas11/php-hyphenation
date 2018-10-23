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
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Model\HyphenatedWords;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Timer\Timer;
use Psr\Log\LoggerInterface;

class WordsHyphenationWithDbModelAction implements ModelAction
{
    private $output;
    private $timer;
    private $modelFactory;
    private $logger;
    private $inputWords;
    private $algorithmName;

    public function __construct(
        HyphenationInput $modelInput,
        BufferedOutput $output,
        ModelFactory $modelFactory,
        LoggerInterface $logger
    ) {
        $this->timer = new Timer();
        $this->output = $output;
        $this->modelFactory = $modelFactory;
        $this->logger = $logger;
        $this->inputWords = $modelInput->getWordsInput();
        $this->algorithmName = $modelInput->getAlgorithmName();
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
            $patterns = $this->modelFactory->getKnownPatterns()->getPatterns();
            $algorithm = new $this->algorithmName($patterns, $this->logger);
            $runner = new AlgorithmRunner($algorithm);
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