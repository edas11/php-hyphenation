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
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Model\CompositeModel;
use Edvardas\Hyphenation\Hyphenator\Model\WordPatterns;
use Edvardas\Hyphenation\Hyphenator\Model\Words;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Timer\Timer;

class ComplexHyphenateWordsAction implements Action
{
    private $output;
    private $dataProvider;
    private $timer;
    private $modelFactory;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->timer = new Timer();
        $this->output = $dataProvider->getOutput();
        $this->dataProvider = $dataProvider;
        $this->modelFactory = $this->dataProvider->getModelFactory();
    }

    public function execute()
    {
        $inputWords = $this->dataProvider->getWords();
        $patterns = $this->dataProvider->getPatterns()->getPatterns();
        $algorithm = $this->dataProvider->getAlgorithm($patterns);
        $runner = new AlgorithmRunner($algorithm);

        $this->timer->start();

        $dbWords = $this->modelFactory->getKnownWords($inputWords);
        $wordsInDb = $dbWords->getOriginalWords();
        $wordsNotInDb = array_values(array_diff($inputWords, $wordsInDb));

        $resultWords = [];
        if (count($wordsNotInDb) > 0) {
            $runner->run($wordsNotInDb, true);
            $resultWords = $runner->getHyphenatedWords();
            $hyphnatedWords = $this->modelFactory->createWords(array_combine($wordsNotInDb, $resultWords));
            $wordPatterns = $this->modelFactory->createWordPatterns($runner->getMatchedPatterns());
            $this->modelFactory->createCompositeModel([$hyphnatedWords, $wordPatterns])->persist();
        }

        $matchedPatternsResult = $this->modelFactory->getKnownWordPatterns($inputWords)->getMatchedPatterns();
        $this->output->printMatchedPatterns($matchedPatternsResult);
        $this->output->printHyphenatedWords(
            array_combine($wordsNotInDb, $resultWords),
            array_combine($wordsInDb, $dbWords->getHyphenatedWords())
        );
        $this->printTime();
    }

    public function printTime(): void
    {
        $time = $this->timer->getInterval();
        $this->output->printTime($time);
        App::$logger->info("Finished in $time seconds.");
    }

}