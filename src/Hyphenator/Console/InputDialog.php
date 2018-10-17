<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 15.06
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Console;

class InputDialog
{
    private $input;
    private $actionInput = 0;
    private $sourceInput = 0;
    private $wordsInput = '';
    private $algorithmInput = 0;

    public function __construct(ConsoleInput $input)
    {
        $this->input = $input;
        $this->start();
    }

    public function start()
    {
        $this->actionInput = $this->input->getActionInput();
        if ($this->actionInput === InputCodes::PUT_PATTERNS_IN_DB_ACTION) {
            $this->sourceInput = InputCodes::DB_SRC;
            return;
        }
        $this->sourceInput = $this->input->getSourceInput();
        $this->wordsInput = $this->input->getWordsInput();
        $this->algorithmInput = $this->input->getAlgorithmInput();
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