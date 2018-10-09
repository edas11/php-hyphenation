<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 10.32
 */

namespace Edvardas\Hyphenation\Hyphenator\Database;

use Edvardas\Hyphenation\UtilityComponents\Database\MySqlDatabase;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlQueryBuilder;

class HyphenationDatabase
{
    private $pdo;
    private $db;
    private $builder;
    private $patterns;

    public function __construct()
    {
        $this->builder = new MySqlQueryBuilder();
        $this->db = new MySqlDatabase();
        $host = '127.0.0.1';
        $db   = 'hyph';
        $user = 'root';
        $pass = 'password';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new \PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getPatternsFromDB()
    {
        $query = $this->builder->select()->columns(['pattern_id', 'pattern'])->from('patterns')->build();
        $patterns = $this->db->executeAndFetch($query);
        $this->patterns = $patterns;
        $patterns = array_map(function($value){
            return $value['pattern'];
        }, $patterns);
        return $patterns;
    }

    public function getKnownHyphenatedWordsFromDB(array $words): array
    {
        $query = $this->builder->select()->columns(['word', 'word_h'])->from('words')
            ->where()->in('word', $words)->build();
        $hyphenatedWords = $this->db->executeAndFetch($query);
        return $hyphenatedWords;
    }

    public function getWordMatchedPatterns(array $words): array
    {
        $query = $this->builder->select()->columns(['word', 'pattern'])->from('word_patterns')
            ->join('words', 'word_patterns.word_id', 'words.word_id')
            ->join('patterns', 'word_patterns.pattern_id', 'patterns.patter_id')
            ->where()->in('words.word', $words);
        $wordMatchedPatterns = $this->db->executeAndFetch($query);
        return $wordMatchedPatterns;
    }

    public function putPatternsInDB(array $patterns)
    {
        $patternsMatrix = array_map(function ($pattern) {
            return [$pattern];
        }, $patterns);
        $query = $this->builder->insert()->into('patterns', ['pattern'])->values($patternsMatrix)->build();
        $this->db->execute($query);
    }

    public function putWordsAndMatchedPatternsInDB(array $words, array $hyphWords, array $matchedPatternsAll)
    {
        if (count($words) !== count($hyphWords) || count($hyphWords) !== count($matchedPatternsAll)) {
            throw new \Exception('All 3 array must have the same length.');
        }
        $this->getPatternsFromDB();
        $wordsStatement = $this->pdo->prepare("INSERT INTO words (word, word_h) VALUES (?, ?)");
        $patStatement = $this->pdo->prepare("INSERT INTO word_patterns (word_id, pattern_id) VALUES (?, ?)");
        try {
            $this->pdo->beginTransaction();
            foreach ($words as $index => $word)
            {
                $wordsStatement->execute([$word, $hyphWords[$index]]);
                $word_id = $this->pdo->lastInsertId();
                foreach ($matchedPatternsAll[$index] as $pattern) {
                    $patStatement->execute([$word_id, $this->getPatternId($pattern)]);
                }
            }
            $this->pdo->commit();
        }catch (Exception $e){
            $this->pdo->rollback();
            throw $e;
        }
    }

    private function getPatternId(string $pattern): int
    {
        $row = array_search($pattern, array_column($this->patterns, 'pattern'));
        return (int) $this->patterns[$row]['pattern_id'];
    }

    private function getSqlInFromArray(array $inParameters): string
    {
        $inClause = '(';
        foreach ($inParameters as $param) {
            $inClause = $inClause . "'$param',";
        }
        $inClause = rtrim($inClause, ',') . ')';
        return $inClause;
    }

}