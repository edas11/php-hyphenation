<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 10.32
 */

namespace Edvardas\Hyphenation\Hyphenator\Database;


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

    /**
     * All arrays must have same length
     */
    public function putWordsAndMatchedPatternsInDB(array $words, array $hyphWords, array $matchedPatternsAll)
    {
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
        //var_dump($this->patterns[$row]);
        return (int) $this->patterns[$row]['pattern_id'];
    }

}