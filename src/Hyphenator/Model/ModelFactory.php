<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.16
 * Time: 11.34
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Model;

use Edvardas\Hyphenation\UtilityComponents\Config\Config;
use Edvardas\Hyphenation\UtilityComponents\Database\SqlDatabase;
use Edvardas\Hyphenation\UtilityComponents\File\FileReader;
use Psr\Log\LoggerInterface;

class ModelFactory
{
    private $db;
    private $fileReader;
    private $logger;
    private $config;

    public function __construct(SqlDatabase $db, Config $config, FileReader $fileReader, LoggerInterface $logger)
    {
        $this->db = $db;
        $this->config = $config;
        $this->fileReader = $fileReader;
        $this->logger = $logger;
    }

    public function createHyphenatedWords(array $words): HyphenatedWords
    {
        return new HyphenatedWords($words, $this->db);
    }

    public function getKnownHyphenatedWords(array $filterWords = []): HyphenatedWords
    {
        return HyphenatedWords::getKnownIn($this->db, $filterWords);
    }

    public function createPatternsModel(array $patterns): Patterns
    {
        return new Patterns($patterns, $this->db);
    }

    public function getKnownPatterns(int $page = 0): Patterns
    {
        $perPage = (int) $this->config->get(['patternsPerPage'], '20');
        return Patterns::getKnown($this->db, $page, $perPage);
    }

    public function getKnownPatternsFromFile(): array
    {
        $patternsFileName = $this->config->get(['patternsFileName']);
        return Patterns::getKnownFromFile($this->fileReader, $patternsFileName, $this->logger);
    }

    public function createWordPatterns(array $wordPatterns): WordPatterns
    {
        return new WordPatterns($wordPatterns,$this->db);
    }

    public function getKnownWordPatterns(array $filterWords): WordPatterns
    {
        return WordPatterns::getKnown($filterWords, $this->db);
    }

    public function createCompositeModel(array $models): CompositeModel
    {
        return new CompositeModel($models, $this->db);
    }
}