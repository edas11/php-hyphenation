<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 17.29
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\UtilityComponents\Database;

use Edvardas\Hyphenation\App\App;

class MySqlQueryBuilder
{
    private $queryString = '';
    private $bindParams = [];
    private $counter = 0; //for prepared statement parameters
    private $isInsertValuesCalled = false;

    public function build(): MySqlQuery
    {
        $query = new MySqlQuery($this->queryString, $this->bindParams);
        $this->reset();
        return $query;
    }

    public function select(): MySqlQueryBuilder
    {
        $this->queryString = $this->queryString . 'SELECT ';
        return $this;
    }

    /**
     * @param string[] $columns
     */
    public function columns(array $columns): MySqlQueryBuilder
    {
        $columnsString = implode(', ', $columns);
        $this->queryString = $this->queryString . "$columnsString ";
        return $this;
    }

    public function from(string $table): MySqlQueryBuilder
    {
        $this->queryString = $this->queryString . "FROM $table ";
        return $this;
    }

    public function join(string $table, string $joinColumnLeft, string $joinColumnRight): MySqlQueryBuilder
    {
        $this->queryString = $this->queryString . "INNER JOIN $table ON $joinColumnLeft = $joinColumnRight ";
        return $this;
    }

    public function where(): MySqlQueryBuilder
    {
        $this->queryString = $this->queryString . 'WHERE ';
        return $this;
    }

    public function equals(string $column, string $value): MySqlQueryBuilder
    {
        $nextCounter = $this->nextCounter();
        $paramName = ":value$nextCounter";
        $this->queryString = $this->queryString . "$column=$paramName ";
        $this->bindParams[$paramName] = $value;
        return $this;
    }

    public function and(): MySqlQueryBuilder
    {
        $this->queryString = $this->queryString . 'AND ';
        return $this;
    }

    public function in(string $column, array $values): MySqlQueryBuilder
    {
        $namedParams = [];
        foreach ($values as $val) {
            $nextCounter = $this->nextCounter();
            $namedParams[":value$nextCounter"] = $val;
        }
        $this->bindParams = array_merge($this->bindParams, $namedParams);
        $inParams = $this->getSqlInParamString($namedParams);
        $this->queryString = $this->queryString . "$column IN $inParams ";
        return $this;
    }

    public function limit(int $start, int $length): MySqlQueryBuilder
    {
        $this->queryString = $this->queryString . "LIMIT $start, $length ";
        return $this;
    }

    public function insert(): MySqlQueryBuilder
    {
        $this->queryString = $this->queryString . 'INSERT ';
        return $this;
    }

    public function replace(): MySqlQueryBuilder
    {
        $this->queryString = $this->queryString . 'REPLACE ';
        return $this;
    }

    public function into(string $table, array $columns): MySqlQueryBuilder
    {
        $columnsString = '(' . implode(', ', $columns) . ')';
        $this->queryString = $this->queryString . "INTO $table$columnsString ";
        return $this;
    }

    public function values(array $valuesRow): MySqlQueryBuilder
    {
        $namesRow = $this->createNamesRow($valuesRow);
        $this->bindParams = array_merge($this->bindParams, array_combine($namesRow, $valuesRow));
        $insertNamesString = '(' . implode(',', $namesRow) . ')';
        $prependToken = $this->isInsertValuesCalled ? ',' : 'VALUES';
        $this->queryString = $this->queryString . "$prependToken $insertNamesString ";
        $this->isInsertValuesCalled = true;
        return $this;
    }

    public function delete(): MySqlQueryBuilder
    {
        $this->queryString = $this->queryString . 'DELETE ';
        return $this;
    }

    public function update(string $table): MySqlQueryBuilder
    {
        $this->queryString = $this->queryString . "UPDATE $table ";
        return $this;
    }

    /**
     * @param string[] $newValues indexes are column names
     */
    public function set(array $newValues): MySqlQueryBuilder
    {
        $this->queryString = $this->queryString . 'SET ';
        $setClauses = [];
        foreach ($newValues as $column => $value) {
            $paramName = ":value" . $this->nextCounter();
            $this->bindParams[$paramName] = $value;
            array_push($setClauses, "$column=$paramName");
        }
        $this->queryString = $this->queryString . implode(',', $setClauses) . " ";
        return $this;
    }

    private function nextCounter(): int
    {
        $this->counter++;
        return $this->counter;
    }

    private function getSqlInParamString(array $namedParams): string
    {
        $namedParamsKeys = array_keys($namedParams);
        $inParams = '(' . implode(',', $namedParamsKeys) . ')';
        return $inParams;
    }

    private function reset(): void
    {
        $this->queryString = '';
        $this->bindParams = [];
        $this->counter = 0;
        $this->isInsertValuesCalled = false;
    }

    private function createNamesRow(array $valuesRow): array
    {
        $namesRow = [];
        foreach ($valuesRow as $value) {
            $nextCounter = $this->nextCounter();
            $name = ":value$nextCounter";
            array_push($namesRow, $name);
        }
        return $namesRow;
    }
}