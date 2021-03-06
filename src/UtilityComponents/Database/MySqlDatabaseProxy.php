<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.15
 * Time: 15.57
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\UtilityComponents\Database;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\UtilityComponents\Config\Config;
use Edvardas\Hyphenation\UtilityComponents\Database\DbDataMappingStrategy;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlDatabase;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlQuery;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlQueryBuilder;
use \Edvardas\Hyphenation\UtilityComponents\Database\SqlDatabase;
use Edvardas\Hyphenation\UtilityComponents\Database\TransactionToken;

class MySqlDatabaseProxy implements SqlDatabase
{
    /**
     * @var MySqlDatabase
     */
    private $mysqlDb = null;
    private $host;
    private $port;
    private $db;
    private $user;
    private $pass;
    private $charset;

    public function __construct(string $host, string $port,string $db, string $user, string $pass, string $charset)
    {
        $this->host = $host;
        $this->port = $port;
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
        $this->charset = $charset;
    }

    public function beginTransaction(): TransactionToken
    {
        $this->checkDbInstance();
        return $this->mysqlDb->beginTransaction();
    }

    public function commit(TransactionToken $token)
    {
        $this->checkDbInstance();
        $this->mysqlDb->commit($token);
    }

    public function executeAndFetch(MySqlQuery $query, DbDataMappingStrategy $mapping = null): array
    {
        $this->checkDbInstance();
        return $this->mysqlDb->executeAndFetch($query, $mapping);
    }

    public function execute(MySqlQuery $query): void
    {
        $this->checkDbInstance();
        $this->mysqlDb->execute($query);
    }

    public function builder(): MySqlQueryBuilder
    {
        $this->checkDbInstance();
        return $this->mysqlDb->builder();
    }

    private function checkDbInstance()
    {
        if (is_null($this->mysqlDb)) {
            $this->mysqlDb = new MySqlDatabase($this->host, $this->port, $this->db, $this->user, $this->pass, $this->charset);
        }
    }
}