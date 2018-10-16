<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.58
 */

namespace Edvardas\Hyphenation\Hyphenator\Providers;

use Edvardas\Hyphenation\Hyphenator\Action\HyphenateAndAddToDbAction;
use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Input\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Input\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\Model\Patterns;
use Edvardas\Hyphenation\Hyphenator\Output\HyphenationOutput;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class HyphenationHttpDataProvider implements HyphenationDataProvider
{
    private $output;
    private $modelFactory;
    private $cache;
    private $logger;
    private $wordsArray = [];
    private $hyphenatedWordsArray = [];

    public function __construct(
        HyphenationOutput $output,
        ModelFactory $modelFactory,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        $this->modelFactory = $modelFactory;
        $this->output = $output;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function getOutput(): HyphenationOutput
    {
        return $this->output;
    }

    public function getModelFactory(): ModelFactory
    {
        return $this->modelFactory;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function getWords(): array
    {
        return $this->wordsArray;
    }

    public function getHyphenatedWords(): array
    {
        return $this->hyphenatedWordsArray;
    }

    public function getAlgorithm($patterns): HyphenationAlgorithmInterface
    {
        return new FullTreeHyphenationAlgorithm($patterns, $this->cache, $this->logger);
    }

    public function getPatterns(): Patterns
    {
        $patterns = $this->modelFactory->getKnownPatterns();
        return $patterns;
    }

    /**
     * @param string[] $words
     */
    public function setWords(array $words)
    {
        $this->wordsArray = $words;
    }

    /**
     * @param string[] $hyphenatedWordsArray
     */
    public function setHyphenatedWords(array $hyphenatedWordsArray)
    {
        $this->hyphenatedWordsArray = $hyphenatedWordsArray;
    }
}