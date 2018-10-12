<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.58
 */

namespace Edvardas\Hyphenation\Hyphenator\Providers;


use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateAndAddToDbAction;
use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Algorithm\ShortTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Input\ConsoleInput;
use Edvardas\Hyphenation\Hyphenator\Input\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Input\InputCodes;
use Edvardas\Hyphenation\Hyphenator\Model\Patterns;
use Edvardas\Hyphenation\Hyphenator\Output\HyphenationOutput;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;

class HyphenationConsoleDataProvider implements HyphenationDataProvider
{
    private $input;
    private $output;

    public function __construct(ConsoleInput $input, HyphenationOutput $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function getOutput(): HyphenationOutput
    {
        return $this->output;
    }

    public function getWords(): array
    {
        $wordsInput = $this->input->getWordsInput();
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
        $hyphenatedWordsInput = $this->input->getHyphenatedWordsInput();
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
        $algorithmChoice = $this->input->getAlgorithmInput();
        switch ($algorithmChoice) {
            case InputCodes::FULL_TREE_ALGORITHM:
                return new FullTreeHyphenationAlgorithm($patterns);
                break;
            case InputCodes::SHORT_TREE_ALGORITHM:
                return new ShortTreeHyphenationAlgorithm($patterns);
                break;
            default:
                return new FullTreeHyphenationAlgorithm($patterns);
        }
    }

    public function getPatterns(): Patterns
    {
        if ($this->input->getSourceInput() === InputCodes::DB_SRC) {
            $patterns = Patterns::getKnown();
        } else {
            $patternsFileName = App::getConfig()->get(['patternsFileName'], 'patterns');
            $patterns = file($patternsFileName, FILE_IGNORE_NEW_LINES);
            if ($patterns === false) {
                App::$logger->error("Could not read patterns file.");
                exit;
            }
            $patterns = array_map(function ($pattern) {
                return ['pattern' => $pattern];
            }, $patterns);
            $patterns = new Patterns($patterns);
        }
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