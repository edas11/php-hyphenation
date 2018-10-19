<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.58
 */
declare(strict_types = 1);
namespace Edvardas\Hyphenation\Hyphenator\Providers;

use Edvardas\Hyphenation\Hyphenator\Action\HyphenateAndAddToDbAction;
use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Console\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Console\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class HttpDataProviderFactory
{
    private $modelFactory;
    private $cache;
    private $logger;
    private $wordsArray = [];
    private $hyphenatedWordsArray = [];

    public function __construct(
        ModelFactory $modelFactory,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        $this->modelFactory = $modelFactory;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function build(): HyphenationDataProvider
    {
        return new HyphenationDataProvider(
            $this->getPatternsInput(),
            $this->wordsArray,
            $this->hyphenatedWordsArray,
            $this->modelFactory,
            $this->getAlgorithm(),
            $this->logger
        );
    }

    public function getAlgorithm(): HyphenationAlgorithmInterface
    {
        return new FullTreeHyphenationAlgorithm($this->getPatternsInput(), $this->cache, $this->logger);
    }

    public function getPatternsInput(): array
    {
        $patterns = $this->modelFactory->getKnownPatterns()->getPatterns();
        return $patterns;
    }

    public function configureWebOutput(string $contentType, string $pagePath = '')
    {
        $this->output->configureOutput($contentType, $pagePath);
    }

    public function setWords(array $words)
    {
        $this->wordsArray = $words;
    }

    public function setHyphenatedWords(array $hyphenatedWordsArray)
    {
        $this->hyphenatedWordsArray = $hyphenatedWordsArray;
    }
}