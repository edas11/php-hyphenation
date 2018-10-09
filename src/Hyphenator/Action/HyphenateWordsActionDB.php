<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.25
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Model\WordPatterns;
use Edvardas\Hyphenation\Hyphenator\Model\Words;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Output\ConsoleOutput;
use Edvardas\Hyphenation\UtilityComponents\Timer\Timer;

class HyphenateWordsActionDB implements Action
{
    private $output;
    private $dataProvider;
    private $timer;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->timer = new Timer();
        $this->output = new ConsoleOutput();
        $this->dataProvider = $dataProvider;
    }

    public function execute()
    {

        $inputWords = $this->dataProvider->getWords();
        $patterns = $this->dataProvider->loadPatterns(true)->getPatterns();
        $algorithm = $this->dataProvider->getAlgorithm($patterns);

        $this->timer->start();

        $dbWords = Words::getKnown($inputWords);
        $wordsInDb = $dbWords->getOriginalWords();
        $wordsNotInDb = array_diff($inputWords, $wordsInDb);

        $resultWords = [];
        $matchedPatternsAll = [];
        foreach ($wordsNotInDb as $inputWord) {
            $word = $algorithm->execute($inputWord);
            array_push($matchedPatternsAll, $algorithm->getMatchedPatterns());
            array_push($resultWords, $word);
        }

        WordPatterns::putWordsAndMatchedPatterns($wordsNotInDb, $resultWords, $matchedPatternsAll);
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