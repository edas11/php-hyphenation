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

    public function putPatternsInDB(array $patterns)
    {
        $statement = $this->pdo->prepare("INSERT INTO patterns (pattern) VALUES (?)");
        try {
            $this->pdo->beginTransaction();
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
}