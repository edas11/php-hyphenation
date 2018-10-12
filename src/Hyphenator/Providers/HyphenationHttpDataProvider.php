<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.58
 */

namespace Edvardas\Hyphenation\Hyphenator\Providers;


use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Action\BadRequestAction;
use Edvardas\Hyphenation\Hyphenator\Action\DeleteWordAction;
use Edvardas\Hyphenation\Hyphenator\Action\GetKnownWordsAction;
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateAndAddToDbAction;
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateWordsActionDB;
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateWordsActionFile;
use Edvardas\Hyphenation\Hyphenator\Action\PutPatternsInDbAction;
use Edvardas\Hyphenation\Hyphenator\Action\PutWordAction;
use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Algorithm\ShortTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Input\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Input\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Input\InputCodes;
use Edvardas\Hyphenation\Hyphenator\Model\Patterns;
use Edvardas\Hyphenation\Hyphenator\Output\HyphenationOutput;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;

class HyphenationHttpDataProvider implements HyphenationDataProvider
{
    private $input;
    private $output;
    private $wordsInput;

    public function __construct(HttpInput $input, HyphenationOutput $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function getOutput(): HyphenationOutput
    {
        return $this->output;
    }

    public function setWordsInput(string $words)
    {
        $this->wordsInput = $words;
    }

    public function getWords(): array
    {
        $wordsInput = $this->wordsInput;
        if ($wordsInput === '') {
            $words = $this->loadWords();
        } else {
            $words = explode(' ', $wordsInput);
        }
        $this->turnOffLoggerIfMoreWordsThanThreshold($words);
        return $words;
    }

    public function getHyphenatedWords(): array
    {
        $body = HttpRequest::getBody();
        if (!array_key_exists('hyphenatedWord', $body) || !is_string($body['hyphenatedWord'])) {
            return '';
        }
        $hyphenatedWordsInput = $body['hyphenatedWord'];
        return explode(' ', $hyphenatedWordsInput);
    }

    private function turnOffLoggerIfMoreWordsThanThreshold(array $inputWords): void
    {
        if (count($inputWords) > App::WORDS_THRESHOLD) {
            App::$logger->notice('Too many words, disabling logger.');
            App::$logger = new NullLogger();
        }
    }

    public function getAlgorithm($patterns): HyphenationAlgorithmInterface
    {
        return new FullTreeHyphenationAlgorithm($patterns);
    }

    public function loadPatterns(): Patterns
    {
        $patterns = Patterns::getKnown();
        return $patterns;
    }

    private function loadWords(): array
    {
        $wordsFileName = App::getConfig()->get(['wordsFileName'], 'words.txt');
        App::$logger->info("Reading words from $wordsFileName file.");
        $words = file($wordsFileName, FILE_IGNORE_NEW_LINES);
        if ($words === false) {
            App::$logger->error("Could not read $wordsFileName file.");
            exit;
        }
        return $words;
    }
}