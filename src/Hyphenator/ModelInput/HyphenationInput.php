<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 15.27
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\ModelInput;

use Edvardas\Hyphenation\Hyphenator\Algorithm\AbstractHyphenationAlgorithm;

class HyphenationInput
{
    private $wordsInput;
    private $hyphenatedWordsInput;
    private $algorithmName;
    private $patternsInput;

    public function __construct(
        array $patternsInput,
        array $wordsInput,
        array $hyphenatedWordsInput,
        string $algorithmName
    ) {
        $this->patternsInput = $patternsInput;
        $this->wordsInput = $wordsInput;
        $this->hyphenatedWordsInput = $hyphenatedWordsInput;
        $this->algorithmName = $algorithmName;
    }

    public function getWordsInput(): array
    {
        return $this->wordsInput;
    }

    public function getHyphenatedWordsInput(): array
    {
        return $this->hyphenatedWordsInput;
    }

    public function getAlgorithmName(): string
    {
        return $this->algorithmName;
    }

    public function getPatternsInput(): array
    {
        return $this->patternsInput;
    }
}