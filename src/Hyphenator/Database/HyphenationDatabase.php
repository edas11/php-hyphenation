<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 10.32
 */

namespace Edvardas\Hyphenation\Hyphenator\Database;

use Edvardas\Hyphenation\UtilityComponents\Database\MySqlDatabase;

class HyphenationDatabase
{
    private $pdo;
    private $patterns;

    public function __construct()
    {
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
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->query('SELECT pattern_id, pattern FROM patterns');
            $patterns = $stmt->fetchAll();
            $this->pdo->commit();
        }catch (Exception $e){
            $this->pdo->rollback();
            throw $e;
        }
        $this->patterns = $patterns;
        $patterns = array_map(function($value){
            return $value['pattern'];
        }, $patterns);
        return $patterns;
    }

    public function getKnownHyphenatedWordsFromDB(array $words): array
    {
        $query = 'SELECT word, word_h FROM words WHERE word in (';
        foreach ($words as $index => $word) {
            $query = $query . ":word$index, ";
        }
        $query = rtrim($query, ', ') . ')';
        $statement = $this->pdo->prepare($query);

        try {
            $this->pdo->beginTransaction();
            foreach ($words as $index => $word) {
                $statement->bindParam("word$index", $word);
            }
            $statement->execute();
            $hyphenatedWords = $statement->fetchAll();
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
        return $hyphenatedWords;
    }

    public function getWordMatchedPatterns(array $words): array
    {
        $inClause = $this->getSqlInFromArray($words);
        $query = 'SELECT word, pattern FROM words, patterns, word_patterns '.
            'WHERE words.word_id=word_patterns.word_id AND patterns.pattern_id=word_patterns.pattern_id '.
            'AND words.word IN '.$inClause;
        $statement = $this->pdo->prepare($query);
        try {
            $this->pdo->beginTransaction();
            $statement->execute([]);
            $result = $statement->fetchAll();
            $this->pdo->commit();
        }catch (Exception $e){
            $this->pdo->rollback();
            throw $e;
        }
        return $result;
    }

    public function putPatternsInDB(array $patterns)
    {
        $statement = $this->pdo->prepare("INSERT INTO patterns (pattern) VALUES (?)");
        try {
            $this->pdo->beginTransaction();
            $this->pdo->query('DELETE FROM patterns');
            foreach ($patterns as $pattern)
            {
                $statement->execute([$pattern]);
            }
            $this->pdo->commit();
        }catch (Exception $e){
            $this->pdo->rollback();
            throw $e;
        }
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