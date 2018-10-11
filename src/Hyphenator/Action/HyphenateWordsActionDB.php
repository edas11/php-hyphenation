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

class HyphenateWordsActionDB implements Action
{
    private $output;
    private $dataProvider;
    private $timer;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->timer = new Timer();
        $this->output = $dataProvider->getOutput();
        $this->dataProvider = $dataProvider;
    }

    public function execute()
    {
        $inputWords = $this->dataProvider->getWords();
        $patterns = $this->dataProvider->loadPatterns()->getPatterns();
        $algorithm = $this->dataProvider->getAlgorithm($patterns);
        $runner = new AlgorithmRunner($algorithm);

        $this->timer->start();

        $dbWords = Words::getKnown($inputWords);
        $wordsInDb = $dbWords->getOriginalWords();
        $wordsNotInDb = array_values(array_diff($inputWords, $wordsInDb));

        $resultWords = [];
        if (count($wordsNotInDb) > 0) {
            $runner->run($wordsNotInDb);
            $resultWords = $runner->getHyphenatedWords();
            $hyphnatedWords = Words::newFromColumnArrays($wordsNotInDb, $resultWords);
            $wordPatterns = new WordPatterns($runner->getMatchedPatterns());
            (new CompositeModel([$hyphnatedWords, $wordPatterns]))->persist();
        }

        $matchedPatternsResult = WordPatterns::getKnown($inputWords)->getMatchedPatterns();
        $this->output->printResult($matchedPatternsResult);
        $this->output->printResult($dbWords->getHyphenatedWords());
        $this->output->printResult($resultWords);

        $this->printTime();
    }

    public function printTime(): void
    {
        $time = $this->timer->getInterval();
        $this->output->printTime($time);
        App::$logger->info("Finished in $time seconds.");
    }

}