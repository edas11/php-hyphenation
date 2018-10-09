<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 17.08
 */

namespace Edvardas\Hyphenation\UtilityComponents\Database;

use Edvardas\Hyphenation\App\App;

class MySqlDatabase
{
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

    /*
    public function beginTransaction()
    {
        try {
            $this->pdo->beginTransaction();
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function commit()
    {
        try {
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }
*/
    public function executeAndFetch(MySqlQuery $query): array
    {
        $statement = $this->pdo->prepare($query->getQueryString());
        try {
            $this->pdo->beginTransaction();
            $statement->execute($query->getBindParams());
            $fetchedData = $statement->fetchAll();
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
        return $fetchedData;
    }

    public function execute(MySqlQuery $query): void
    {
        $ids = [];
        $statement = $this->pdo->prepare($query->getQueryString());
        try {
            $this->pdo->beginTransaction();
            $statement->execute($query->getBindParams());
            $this->pdo->commit();
        }catch (Exception $e){
            $this->pdo->rollback();
            throw $e;
        }
    }
}