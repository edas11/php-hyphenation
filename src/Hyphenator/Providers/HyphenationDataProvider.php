<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.58
 */

namespace Edvardas\Hyphenation\Hyphenator\Providers;


use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\UtilityComponents\Input\ConsoleInput;
use Edvardas\Hyphenation\UtilityComponents\Output\ConsoleOutput;

class HyphenationDataProvider
{
    private $input;
    private $output;

    public function __construct($config)
    {
        $this->config = $config;
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

    public function getWordsInput(): string
    {
        $this->output->printLn("Write words separated by spaces or leave empty to hyphenate words in file.");
        return (string)$this->input->getInput();
    }

    public function getAlgorithmInput(): int
    {
        $this->output->printLn("Choose algorithm:");
        $this->output->printLn("(1) Full tree");
        $this->output->printLn("(2) Short tree");
        return (int)$this->input->getInput();
    }

    public function loadPatterns(): array
    {
        $this->output->printLn("Choose patterns source:");
        $this->output->printLn("(1) File");
        $this->output->printLn("(2) Database");
        $sourceInput = (int)$this->input->getInput();
        if ($sourceInput === 2) {
            $db = new HyphenationDatabase();
            $patterns = $db->getPatternsFromDB();
        } else {
            $this->output->printLn("Loading patterns");
            $patternsFileName = $this->config->get('patternsFileName', 'patterns');
            $patterns = file($patternsFileName, FILE_IGNORE_NEW_LINES);
            if ($patterns === false) {
                App::$logger->error("Could not read patterns file.");
                exit;
            }
        }
        return $patterns;
    }

    public function loadWords(): array
    {
        $wordsFileName = $this->config->get('wordsFileName', 'words.txt');
        App::$logger->info("Reading words from $wordsFileName file.");
        $words = file($wordsFileName, FILE_IGNORE_NEW_LINES);
        if ($words === false) {
            App::$logger->error("Could not read $wordsFileName file.");
            exit;
        }
        return $words;
    }
}