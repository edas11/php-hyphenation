<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.25
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;
use Edvardas\Hyphenation\UtilityComponents\Output\ConsoleOutput;

class HyphenateWordsActionFile implements Action
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
        $inputWords = $this->dataProvider->getWords();
        $patterns = $this->dataProvider->loadPatterns(false);
        $algorithm = $this->dataProvider->getAlgorithm($patterns);

        $this->turnOffLoggerIfMoreWordsThanThreshold($inputWords);

        $resultWords = [];
        foreach ($inputWords as $inputWord) {
            $word = $algorithm->execute($inputWord);
            array_push($resultWords, $word);
        }

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