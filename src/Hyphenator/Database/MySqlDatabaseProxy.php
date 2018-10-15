<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.15
 * Time: 15.57
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Database;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlDatabase;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlQuery;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlQueryBuilder;
use \Edvardas\Hyphenation\UtilityComponents\Database\SqlDatabase;

class MySqlDatabaseProxy implements SqlDatabase
{
    /**
     * @var MySqlDatabase
     */
    private $mysqlDb = null;

    public function beginTransaction()
    {
        $this->checkDbInstance();
        $this->mysqlDb->beginTransaction();
    }

    public function commit()
    {
        $this->checkDbInstance();
        $this->mysqlDb->commit();
    }

    public function executeAndFetch(MySqlQuery $query): array
    {
        $this->checkDbInstance();
        return $this->mysqlDb->executeAndFetch($query);
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
            $host = App::getConfig(['mysql', 'host']);
            $db = App::getConfig(['mysql', 'db']);
            $user = App::getConfig(['mysql', 'user']);
            $pass = App::getConfig(['mysql', 'password']);
            $charset = App::getConfig(['mysql', 'charset']);
            $this->mysqlDb = new MySqlDatabase($host, $db, $user, $pass, $charset);
        }
    }
}