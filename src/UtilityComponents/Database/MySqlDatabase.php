<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 17.08
 */

namespace Edvardas\Hyphenation\UtilityComponents\Database;

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

    public function select(array $selectColumns, string $selectTables, array $where)
    {
        $query = '';
        foreach ($selectColumns as $column) {
            $query = $query . "$column, ";
        }
        $query = rtrim($query, ', ') . ')';
        $query = $query . " FROM $selectTables";
        $query = $query . " WHERE";
        foreach ($where as $whereClause) {

        }
    }
}