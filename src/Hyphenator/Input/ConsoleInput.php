<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 09.08
 */

namespace Edvardas\Hyphenation\Hyphenator\Input;


use Edvardas\Hyphenation\UtilityComponents\Console\Console;

class ConsoleInput
{
    private $console;
    private $actionInput;
    private $sourceInput;
    private $wordsInput;
    private $algorithmInput;

    public function __construct()
    {
        $this->console = new Console();
    }

    public function getActionInput(): int
    {
        if (is_null($this->actionInput)) {
            $this->console->printLn("Started hyphenation algorithm.");
            $this->console->printLn("Choose action:");
            $this->console->printLn("(".InputCodes::HYPHENATE_ACTION.") Hyphenate words");
            $this->console->printLn("(".InputCodes::PUT_PATTERNS_IN_DB_ACTION.") Load patterns to database");
            $this->actionInput = (int)$this->console->getInput();
        }
        return $this->actionInput;
    }

    public function getSourceInput(): int
    {
        if (is_null($this->sourceInput)) {
            if ($this->actionInput === InputCodes::PUT_PATTERNS_IN_DB_ACTION) {
                return InputCodes::FILE_SRC;
            }
            $this->console->printLn("Choose hyphenation source:");
            $this->console->printLn("(".InputCodes::FILE_SRC.") File");
            $this->console->printLn("(".InputCodes::DB_SRC.") Database");
            $this->sourceInput = (int)$this->console->getInput();
        }
        return $this->sourceInput;
    }

    public function getWordsInput(): string
    {
        if (is_null($this->wordsInput)) {
            $this->console->printLn("Write words separated by spaces or leave empty to hyphenate words in file.");
            $this->wordsInput = (string)$this->console->getInput();
        }
        return $this->wordsInput;
    }

    public function getAlgorithmInput(): int
    {
        if (is_null($this->algorithmInput)) {
            $this->console->printLn("Choose algorithm:");
            $this->console->printLn("(".InputCodes::FULL_TREE_ALGORITHM.") Full tree");
            $this->console->printLn("(".InputCodes::SHORT_TREE_ALGORITHM.") Short tree");
            $this->algorithmInput = (int)$this->console->getInput();
        }
        return $this->algorithmInput;
    }

}