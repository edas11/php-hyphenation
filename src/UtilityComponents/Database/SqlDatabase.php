<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.15
 * Time: 15.53
 */

namespace Edvardas\Hyphenation\UtilityComponents\Database;

interface SqlDatabase
{
    public function beginTransaction(): TransactionToken;

    public function commit(TransactionToken $token);

    public function executeAndFetch(MySqlQuery $query, DbDataMappingStrategy $mapping = null): array;

    public function execute(MySqlQuery $query): void;

    public function builder(): MySqlQueryBuilder;
}