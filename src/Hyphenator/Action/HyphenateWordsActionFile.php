<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.25
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Algorithm\ShortTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Input\ConsoleInput;
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
        $wordsInput = $this->dataProvider->getWordsInput();
        $inputWords = $this->getWordsFromInput($wordsInput);
        $this->turnOffLoggerIfMoreWordsThanThreshold($inputWords);

        $patterns = $this->dataProvider->loadPatterns();

        $algorithmInput = $this->dataProvider->getAlgorithmInput();
        $algorithm = $this->getAlgorithmFromInput($patterns, $algorithmInput);

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

    private function getWordsFromInput(string $wordsInput): array
    {
        if ($wordsInput === '') {
            $words = $this->dataProvider->loadWords();
        } else {
            $words = explode(' ', $wordsInput);
        }
        return $words;
    }

    private function getAlgorithmFromInput(array $patterns, int $algorithmChoice): HyphenationAlgorithmInterface
    {
        switch ($algorithmChoice) {
            case 1:
                return new FullTreeHyphenationAlgorithm($patterns);
                break;
            case 2:
                return new ShortTreeHyphenationAlgorithm($patterns);
                break;
            default:
                return new FullTreeHyphenationAlgorithm($patterns);
        }
    }
}