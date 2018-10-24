<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 15.06
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Console;

use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\ShortTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Controller\ConsoleControllers\PatternsTransferToDbController;
use Edvardas\Hyphenation\Hyphenator\Controller\ConsoleControllers\WordsHyphenationController;
use Edvardas\Hyphenation\Hyphenator\Controller\ConsoleControllers\WordsHyphenationWithDController;
use Edvardas\Hyphenation\UtilityComponents\Console\Console;

class InputDialog
{
    private $console;
    private $actionInput;
    private $sourceInput;
    private $wordsInput;
    private $algorithmInput;

    public function __construct(Console $console)
    {
        $this->console = $console;
        $this->askInput();
    }

    public function getHandlerName(): string
    {
        if ($this->actionInput === InputCodes::PUT_PATTERNS_IN_DB_ACTION) {
            return PatternsTransferToDbController::class;
        } elseif ($this->actionInput === InputCodes::HYPHENATE_ACTION && $this->sourceInput === InputCodes::FILE_SRC) {
            return WordsHyphenationController::class;
        } elseif ($this->actionInput === InputCodes::HYPHENATE_ACTION && $this->sourceInput === InputCodes::DB_SRC) {
            return WordsHyphenationWithDController::class;
        } else {
            return '';
        }
    }

    public function getInputWords()
    {
        if ($this->wordsInput === '') {
            return [];
        } else {
            return explode(' ', $this->wordsInput);
        }
    }

    public function getAlgorithmName(): string
    {
        if ($this->algorithmInput === InputCodes::FULL_TREE_ALGORITHM) {
            return FullTreeHyphenationAlgorithm::class;
        } elseif ($this->algorithmInput === InputCodes::SHORT_TREE_ALGORITHM) {
            return ShortTreeHyphenationAlgorithm::class;
        } else {
            return '';
        }
    }

    private function askInput(): void
    {
        $this->actionInput = $this->askActionInput();
        if ($this->actionInput === InputCodes::PUT_PATTERNS_IN_DB_ACTION) {
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
}