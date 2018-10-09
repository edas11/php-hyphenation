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
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;
use Edvardas\Hyphenation\UtilityComponents\Output\ConsoleOutput;

class HyphenateWordsActionDB implements Action
{
    private $output;
    private $dataProvider;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->output = new ConsoleOutput();
        $this->dataProvider = $dataProvider;
    }

    public function execute()
    {
        $db = new HyphenationDatabase();

        $inputWords = $this->dataProvider->getWords();
        $patterns = $this->dataProvider->loadPatterns(true);
        $algorithm = $this->dataProvider->getAlgorithm($patterns);

        $this->turnOffLoggerIfMoreWordsThanThreshold($inputWords);

        $hyphenatedWords = $db->getKnownHyphenatedWords($inputWords);
        $returnedWords = array_column($hyphenatedWords, 'word');
        $wordsNotInDb = array_diff($inputWords, $returnedWords);

        $resultWords = [];
        $matchedPatternsAll = [];
        foreach ($wordsNotInDb as $inputWord) {
            $word = $algorithm->execute($inputWord);
            array_push($matchedPatternsAll, $algorithm->getMatchedPatterns());
            array_push($resultWords, $word);
        }

        $db->putWordsAndMatchedPatterns($wordsNotInDb, $resultWords, $matchedPatternsAll);

        $matchedPatterns = $db->getWordMatchedPatterns($inputWords);
        $this->output->printResult($matchedPatterns);
        $this->output->printResult(array_column($hyphenatedWords, 'word_h'));
        $this->output->printResult($resultWords);
    }

    private function turnOffLoggerIfMoreWordsThanThreshold(array $inputWords): void
    {
        if (count($inputWords) > App::WORDS_THRESHOLD) {
            App::$logger->notice('Too many words, disabling logger.');
            App::$logger = new NullLogger();
        }
    }
}