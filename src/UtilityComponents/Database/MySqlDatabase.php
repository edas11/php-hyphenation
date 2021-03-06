<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 17.08
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\UtilityComponents\Database;

class MySqlDatabase implements SqlDatabase
{
    private $builder;
    private $currentTransactionToken = null;

    public function __construct(string $host, string $port, string $db, string $user, string $pass, string $charset)
    {
        $this->builder = new MySqlQueryBuilder();

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => true,
        ];
        try {
            $this->pdo = new \PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function beginTransaction(): TransactionToken
    {
        if (!is_null($this->currentTransactionToken)) {
            return new TransactionToken();
        }
        try {
            $this->pdo->beginTransaction();
            $this->currentTransactionToken = new TransactionToken();
            return $this->currentTransactionToken;
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function commit(TransactionToken $token)
    {
        if ($token !== $this->currentTransactionToken) {
            return;
        }
        try {
            $this->pdo->commit();
            $this->currentTransactionToken = null;
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function executeAndFetch(MySqlQuery $query, DbDataMappingStrategy $mapping = null): array
    {
        $statement = $this->pdo->prepare($query->getQueryString());
        try {
            $statement->execute($query->getBindParams());
            $fetchedData = $statement->fetchAll();
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
        if (!is_null($mapping)) {
            $fetchedData = $mapping->map($fetchedData);
        }
        return $fetchedData;
    }

    public function execute(MySqlQuery $query): void
    {
        $statement = $this->pdo->prepare($query->getQueryString());
        try {
            $statement->execute(
                $query->getBindParams()
            );
        }catch (Exception $e){
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function builder(): MySqlQueryBuilder
    {
        return $this->builder;
    }
}