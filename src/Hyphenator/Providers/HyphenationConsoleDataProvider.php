<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.58
 */

namespace Edvardas\Hyphenation\Hyphenator\Providers;


use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateAndAddToDbAction;
use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Algorithm\ShortTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\File\PatternsFile;
use Edvardas\Hyphenation\Hyphenator\File\WordsFile;
use Edvardas\Hyphenation\Hyphenator\Input\ConsoleInput;
use Edvardas\Hyphenation\Hyphenator\Input\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Input\InputCodes;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\Model\Patterns;
use Edvardas\Hyphenation\Hyphenator\Output\HyphenationOutput;
use Edvardas\Hyphenation\UtilityComponents\Config\Config;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class HyphenationConsoleDataProvider implements HyphenationDataProvider
{
    private $input;
    private $output;
    private $config;
    private $modelFactory;
    private $cache;
    private $logger;

    public function __construct(
        ConsoleInput $input,
        HyphenationOutput $output,
        Config $config,
        ModelFactory $modelFactory,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->config = $config;
        $this->modelFactory = $modelFactory;
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

    public function getWordsInput(): array
    {
        $wordsInput = $this->input->getWordsInput();
        if ($wordsInput === '') {
            $wordsFileName = $this->config->get(['wordsFileName'], 'words.txt');
            $this->logger->info("Reading words from $wordsFileName file.");
            $words = WordsFile::getContentsAsArray($wordsFileName, $this->logger);
        } else {
            $words = explode(' ', $wordsInput);
        }
        $this->wordsReadEvent(count($words));
        return $words;
    }

    private function wordsReadEvent(int $numberOfWords): void
    {
        if ($numberOfWords > (int) $this->config->get(['wordsThreshold'])) {
            $this->logger->notice('Too many words, disabling logger.');
            $this->logger = new NullLogger();
        }
    }

    public function getHyphenatedWords(): array
    {
        $hyphenatedWordsInput = $this->input->getHyphenatedWordsInput();
        return explode(' ', $hyphenatedWordsInput);
    }

    public function getAlgorithm(): HyphenationAlgorithmInterface
    {
        $algorithmChoice = $this->input->getAlgorithmInput();
        switch ($algorithmChoice) {
            case InputCodes::FULL_TREE_ALGORITHM:
                return new FullTreeHyphenationAlgorithm($this->getPatternsInput(), $this->cache, $this->logger);
                break;
            case InputCodes::SHORT_TREE_ALGORITHM:
                return new ShortTreeHyphenationAlgorithm($this->getPatternsInput(), $this->cache, $this->logger);
                break;
            default:
                return new FullTreeHyphenationAlgorithm($this->getPatternsInput(), $this->cache, $this->logger);
        }
    }

    public function getPatternsInput(): array
    {
        if ($this->input->getSourceInput() === InputCodes::DB_SRC) {
            $patterns = $this->modelFactory->getKnownPatterns()->getPatterns();
        } else {
            $patternsFileName = $this->config->get(['patternsFileName'], 'patterns');
            $patterns = PatternsFile::getContentsAsArray($patternsFileName, $this->logger);
        }
        return $patterns;
    }
}