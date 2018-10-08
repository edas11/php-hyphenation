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
use Edvardas\Hyphenation\UtilityComponents\Input\ConsoleInput;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;
use Edvardas\Hyphenation\UtilityComponents\Output\ConsoleOutput;

class HyphenateWordsAction implements Action
{
    private $config;
    private $input;
    private $output;
    private $words;
    private $algorithm;

    public function __construct($config)
    {
        $this->config = $config;
        $this->input = new ConsoleInput();
        $this->output = new ConsoleOutput();
    }

    public function execute()
    {
        $this->dialogHyphenateWords();
        $inputWords = $this->words;
        $algorithm = $this->algorithm;
        $result = [];
        foreach ($inputWords as $inputWord) {
            array_push($result, $algorithm->execute($inputWord));
        }
        return $result;
    }

    private function dialogHyphenateWords()
    {
        $this->output->printLn("Write words separated by spaces or leave empty to hyphenate words in file.");
        $wordsInput = (string)$this->input->getInput();
        $this->words = $this->setWords($wordsInput);
        $this->turnOffLoggerIfMoreWordsThanThreshold($this->words);
        $this->output->printLn("Loading patterns");
        $patterns = $this->loadPatterns();
        $this->output->printLn("Choose algorithm:");
        $this->output->printLn("(1) Full tree");
        $this->output->printLn("(2) Short tree");
        $algorithmChoice = (int)$this->input->getInput();
        $this->algorithm = $this->setAlgorithm($patterns, $algorithmChoice);
        $this->output->printLn("Starting execution");
    }

    private function loadPatterns(): array
    {
        $patternsFileName = $this->config->get('patternsFileName', 'patterns');
        $patterns = file($patternsFileName, FILE_IGNORE_NEW_LINES);
        if ($patterns === false) {
            App::$logger->error("Could not read patterns file.");
            exit;
        }
        return $patterns;
    }

    private function turnOffLoggerIfMoreWordsThanThreshold(array $inputWords): void
    {
        if (count($inputWords) > App::WORDS_THRESHOLD) {
            App::$logger->notice('Too many words, disabling logger.');
            App::$logger = new NullLogger();
        }
    }

    private function setWords(string $wordsInput): array
    {
        var_dump($wordsInput);
        if ($wordsInput === '') {
            $wordsFileName = $this->config->get('wordsFileName', 'words.txt');
            App::$logger->info("Reading words from $wordsFileName file.");
            $words = file($wordsFileName, FILE_IGNORE_NEW_LINES);
            if ($words === false) {
                App::$logger->error("Could not read $wordsFileName file.");
                exit;
            }
        } else {
            $words = explode(' ', $wordsInput);
        }
        return $words;
    }

    private function setAlgorithm(array $patterns, int $algorithmChoice): HyphenationAlgorithmInterface
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