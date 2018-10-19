<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 15.27
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Providers;

use Edvardas\Hyphenation\Hyphenator\Algorithm\AbstractHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Psr\Log\LoggerInterface;

class HyphenationDataProvider
{
    private $patterns;
    private $words;
    private $hyphenatedWords;
    private $modelFactory;
    private $algorithm;
    private $logger;

    public function __construct(
        array $patterns,
        array $words,
        array $hyphenatedWords,
        ModelFactory $modelFactory,
        AbstractHyphenationAlgorithm $algorithm,
        LoggerInterface $logger
    ) {
        $this->patterns = $patterns;
        $this->words = $words;
        $this->hyphenatedWords = $hyphenatedWords;
        $this->modelFactory = $modelFactory;
        $this->algorithm = $algorithm;
        $this->logger = $logger;
    }

    public function getModelFactory(): ModelFactory
    {
        return $this->modelFactory;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function getWordsInput(): array
    {
        return $this->words;
    }

    public function getHyphenatedWordsInput(): array
    {
        return $this->hyphenatedWords;
    }

    public function getAlgorithm(): AbstractHyphenationAlgorithm
    {
        return $this->algorithm;
    }

    public function getPatternsInput(): array
    {
        return $this->patterns;
    }
}