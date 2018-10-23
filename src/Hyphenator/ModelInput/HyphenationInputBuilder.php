<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.23
 * Time: 14.38
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\ModelInput;

use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;

class HyphenationInputBuilder
{
    private $wordsInput;
    private $hyphenatedWordsInput;
    private $algorithmName;
    private $patternsInput;

    public function __construct()
    {
        $this->setFieldsToDefaults();
    }

    public function build()
    {
        $input = new HyphenationInput(
            $this->patternsInput,
            $this->wordsInput,
            $this->hyphenatedWordsInput,
            $this->algorithmName
        );
        $this->setFieldsToDefaults();
        return $input;
    }

    public function setWordsInput(array $wordsInput): void
    {
        $this->wordsInput = $wordsInput;
    }

    public function setHyphenatedWordsInput(array $hyphenatedWordsInput): void
    {
        $this->hyphenatedWordsInput = $hyphenatedWordsInput;
    }

    public function setAlgorithmName(string $algorithmName): void
    {
        $this->algorithmName = $algorithmName;
    }

    public function setPatternsInput(array $patternsInput): void
    {
        $this->patternsInput = $patternsInput;
    }

    private function setFieldsToDefaults()
    {
        $this->wordsInput = [];
        $this->hyphenatedWordsInput = [];
        $this->algorithmName = FullTreeHyphenationAlgorithm::class;
        $this->patternsInput = [];
    }
}