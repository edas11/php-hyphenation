<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.15
 * Time: 15.53
 */

namespace Edvardas\Hyphenation\UtilityComponents\Database;

interface SqlQueryBuilder
{
    public function build(): MySqlQuery;

    public function select();

    /**
     * @param string[] $columns
     */
    public function columns(array $columns);

    public function from(string $table);

    public function join(string $table, string $joinColumnLeft, string $joinColumnRight);

    public function where();

    public function equals(string $column, string $value);

    public function and();

    public function in(string $column, array $values);

    public function insert();

    public function into(string $table, array $columns);

    public function values(array $valuesMatrix);

    public function delete();

    public function update(string $table);

    /**
     * @param string[] $newValues indexes are column names
     */
    public function set(array $newValues);
}