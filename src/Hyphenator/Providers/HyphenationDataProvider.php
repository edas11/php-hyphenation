<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.58
 */

namespace Edvardas\Hyphenation\Hyphenator\Providers;


use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Algorithm\ShortTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Model\Patterns;
use Edvardas\Hyphenation\UtilityComponents\Input\ConsoleInput;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;
use Edvardas\Hyphenation\UtilityComponents\Output\ConsoleOutput;

class HyphenationDataProvider
{
    private $input;
    private $output;

    public const HYPHENATE_ACTION = 1;
    public const PUT_PATTERNS_IN_DB_ACTION = 2;
    public const FILE_SRC = 1;
    public const DB_SRC = 2;
    public const FULL_TREE_ALGORITHM = 1;
    public const SHORT_TREE_ALGORITHM = 2;

    public function __construct()
    {
        $this->input = new ConsoleInput();
        $this->output = new ConsoleOutput();
    }

    public function getActionInput(): int
    {
        $this->output->printLn("Started hyphenation algorithm.");
        $this->output->printLn("Choose action:");
        $this->output->printLn("(1) Hyphenate words");
        $this->output->printLn("(2) Load patterns to database");
        $choice = (int)$this->input->getInput();
        return $choice;
    }

    public function getSourceInput(): int
    {
        $this->output->printLn("Choose hyphenation source:");
        $this->output->printLn("(1) File");
        $this->output->printLn("(2) Database");
        return (int)$this->input->getInput();
    }

    public function getWords(): array
    {
        $wordsInput = $this->getWordsInput();
        if ($wordsInput === '') {
            $words = $this->loadWords();
        } else {
            $words = explode(' ', $wordsInput);
        }
        $this->turnOffLoggerIfMoreWordsThanThreshold($words);
        return $words;
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
        $algorithmChoice = $this->getAlgorithmInput();
        switch ($algorithmChoice) {
            case self::FULL_TREE_ALGORITHM:
                return new FullTreeHyphenationAlgorithm($patterns);
                break;
            case self::SHORT_TREE_ALGORITHM:
                return new ShortTreeHyphenationAlgorithm($patterns);
                break;
            default:
                return new FullTreeHyphenationAlgorithm($patterns);
        }
    }

    public function loadPatterns(bool $isFromDb): Patterns
    {
        if ($isFromDb) {
            $patterns = Patterns::getKnown();
            /*$db = new HyphenationDatabase();
            $patterns = $db->getPatterns();
            */
        } else {
            $this->output->printLn("Loading patterns");
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

    private function getWordsInput(): string
    {
        $this->output->printLn("Write words separated by spaces or leave empty to hyphenate words in file.");
        return (string)$this->input->getInput();
    }

    private function getAlgorithmInput(): int
    {
        $this->output->printLn("Choose algorithm:");
        $this->output->printLn("(1) Full tree");
        $this->output->printLn("(2) Short tree");
        return (int)$this->input->getInput();
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