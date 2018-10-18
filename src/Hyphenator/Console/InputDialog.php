<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 15.06
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Console;

use Edvardas\Hyphenation\UtilityComponents\Console\Console;

class InputDialog
{
    private $console;
    private $inputData;

    public function __construct(Console $console)
    {
        $this->console = $console;
        $this->askInput();
    }

    public function getConsoleInput(): ConsoleInputData
    {
        return $this->inputData;
    }

    private function askInput(): void
    {
        $actionInput = $this->askActionInput();
        if ($actionInput === InputCodes::PUT_PATTERNS_IN_DB_ACTION) {
            $sourceInput = InputCodes::DB_SRC;
            $this->inputData = new ConsoleInputData($actionInput, $sourceInput);
            return;
        }
        $sourceInput = $this->askSourceInput();
        $wordsInput = $this->askWordsInput();
        $algorithmInput = $this->askAlgorithmInput();
        $this->inputData = new ConsoleInputData($actionInput, $sourceInput, $wordsInput, $algorithmInput);
    }

    public function askActionInput(): int
    {
        $this->console->printLn("Started hyphenation algorithm.");
        $this->console->printLn("Choose action:");
        $this->console->printLn("(" . InputCodes::HYPHENATE_ACTION . ") Hyphenate words");
        $this->console->printLn("(" . InputCodes::PUT_PATTERNS_IN_DB_ACTION . ") Load patterns to database");
        return (int)$this->console->getInput();
    }

    public function askSourceInput(): int
    {
        $this->console->printLn("Choose hyphenation source:");
        $this->console->printLn("(" . InputCodes::FILE_SRC . ") File");
        $this->console->printLn("(" . InputCodes::DB_SRC . ") Database");
        return (int)$this->console->getInput();
    }

    public function askWordsInput(): string
    {
        $this->console->printLn("Write words separated by spaces or leave empty to hyphenate words in file.");
        return (string)$this->console->getInput();
    }

    public function askAlgorithmInput(): int
    {
        $this->console->printLn("Choose algorithm:");
        $this->console->printLn("(" . InputCodes::FULL_TREE_ALGORITHM . ") Full tree");
        $this->console->printLn("(" . InputCodes::SHORT_TREE_ALGORITHM . ") Short tree");
        return (int)$this->console->getInput();
    }
}