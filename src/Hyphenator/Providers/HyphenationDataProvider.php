<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.12
 * Time: 10.08
 */

namespace Edvardas\Hyphenation\Hyphenator\Providers;

use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\Model\Patterns;
use Edvardas\Hyphenation\Hyphenator\Output\HyphenationOutput;
use Psr\Log\LoggerInterface;

interface HyphenationDataProvider
{
    public function getOutput(): HyphenationOutput;

    public function getModelFactory(): ModelFactory;

    public function getLogger(): LoggerInterface;

    public function getWordsInput(): array;

    public function getHyphenatedWords(): array;

    public function getAlgorithm(): HyphenationAlgorithmInterface;

    public function getPatternsInput(): array;
}