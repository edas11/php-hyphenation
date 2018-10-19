<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.16
 * Time: 11.34
 */

namespace Edvardas\Hyphenation\Hyphenator\Model;


use Edvardas\Hyphenation\UtilityComponents\Database\SqlDatabase;

class ModelFactory
{
    private $db;

    public function __construct(SqlDatabase $db)
    {
        $this->db = $db;
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

    public function getKnownPatterns(): Patterns
    {
        return Patterns::getKnown($this->db);
    }

    public function getPaginatedPatterns(int $page): Patterns
    {
        return Patterns::getPaginated($this->db, $page);
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