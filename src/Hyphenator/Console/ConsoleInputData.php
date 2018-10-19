<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.18
 * Time: 09.03
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Console;

use Edvardas\Hyphenation\Hyphenator\Action\PatternsSaveInDbHyphenationAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordsHyphenationHyphenationAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordsHyphenationWithDbHyphenationAction;
use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\ShortTreeHyphenationAlgorithm;

class ConsoleInputData
{
    private $actionInput;
    private $sourceInput;
    private $wordsInput;
    private $algorithmInput;

    public function __construct(int $actionInput, int $sourceInput, string $wordsInput = null, int $algorithmInput = null)
    {
        $this->actionInput = $actionInput;
        $this->sourceInput = $sourceInput;
        $this->wordsInput = $wordsInput;
        $this->algorithmInput = $algorithmInput;
    }

    public function getActionName(): string
    {
        if ($this->actionInput === InputCodes::HYPHENATE_ACTION && $this->sourceInput === InputCodes::FILE_SRC) {
            return WordsHyphenationHyphenationAction::class;
        } elseif ($this->actionInput === InputCodes::HYPHENATE_ACTION && $this->sourceInput === InputCodes::DB_SRC) {
            return WordsHyphenationWithDbHyphenationAction::class;
        } elseif ($this->actionInput === InputCodes::PUT_PATTERNS_IN_DB_ACTION) {
            return PatternsSaveInDbHyphenationAction::class;
        } else {
            return '';
        }
    }

    public function isWordsFromInput(): bool
    {
        if (is_string($this->wordsInput) && $this->wordsInput !== '') {
            return true;
        } else {
            return false;
        }
    }

    public function isWordsFromFile(): bool
    {
        if ($this->wordsInput === '') {
            return true;
        } else {
            return false;
        }
    }

    public function getWords(): array
    {
        return explode(' ', $this->wordsInput);
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

    public function isPatternsFromDb(): bool
    {
        if ($this->sourceInput === InputCodes::DB_SRC) {
            return true;
        } else {
            return false;
        }
    }
}