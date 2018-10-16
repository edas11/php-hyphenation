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

    public function createWords(array $words): Words
    {
        return new Words($words, $this->db);
    }

    public function getKnownWords(array $filterWords = []): Words
    {
        if (count($filterWords) > 0) {
            return Words::getKnownIn($filterWords, $this->db);
        } else {
            return Words::getKnown($this->db);
        }
    }

    public function createPatternsModel(array $patterns): Patterns
    {
        return new Patterns($patterns, $this->db);
    }

    public function getKnownPatterns(): Patterns
    {
        return Patterns::getKnown($this->db);
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