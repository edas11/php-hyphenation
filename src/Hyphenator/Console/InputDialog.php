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
    private $actionInput = 0;
    private $sourceInput = 0;
    private $wordsInput = '';
    private $algorithmInput = 0;

    public function __construct(Console $console)
    {
        $this->console = $console;
        $this->start();
    }

    public function start()
    {
        $this->actionInput = $this->askActionInput();
        if ($this->actionInput === InputCodes::PUT_PATTERNS_IN_DB_ACTION) {
            $this->sourceInput = InputCodes::DB_SRC;
            return;
        }
        $this->sourceInput = $this->askSourceInput();
        $this->wordsInput = $this->askWordsInput();
        $this->algorithmInput = $this->askAlgorithmInput();
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

    public function getActionInput(): int
    {
        return $this->actionInput;
    }

    public function getSourceInput(): int
    {
        return $this->sourceInput;
    }

    public function getWordsInput(): string
    {
        return $this->wordsInput;
    }

    public function getAlgorithmInput(): int
    {
        return $this->algorithmInput;
    }
}