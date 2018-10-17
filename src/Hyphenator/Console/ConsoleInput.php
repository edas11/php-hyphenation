<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 09.08
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Console;

use Edvardas\Hyphenation\UtilityComponents\Console\Console;

class ConsoleInput
{
    private $console;

    public function __construct()
    {
        $this->console = new Console();
    }

    public function getActionInput(): int
    {
        $this->console->printLn("Started hyphenation algorithm.");
        $this->console->printLn("Choose action:");
        $this->console->printLn("(" . InputCodes::HYPHENATE_ACTION . ") Hyphenate words");
        $this->console->printLn("(" . InputCodes::PUT_PATTERNS_IN_DB_ACTION . ") Load patterns to database");
        return (int)$this->console->getInput();
    }

    public function getSourceInput(): int
    {
        $this->console->printLn("Choose hyphenation source:");
        $this->console->printLn("(" . InputCodes::FILE_SRC . ") File");
        $this->console->printLn("(" . InputCodes::DB_SRC . ") Database");
        return (int)$this->console->getInput();
    }

    public function getWordsInput(): string
    {
        $this->console->printLn("Write words separated by spaces or leave empty to hyphenate words in file.");
        return (string)$this->console->getInput();
    }

    public function getAlgorithmInput(): int
    {
        $this->console->printLn("Choose algorithm:");
        $this->console->printLn("(" . InputCodes::FULL_TREE_ALGORITHM . ") Full tree");
        $this->console->printLn("(" . InputCodes::SHORT_TREE_ALGORITHM . ") Short tree");
        return (int)$this->console->getInput();
    }
}