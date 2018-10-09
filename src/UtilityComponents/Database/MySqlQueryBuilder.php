<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 17.29
 */

namespace Edvardas\Hyphenation\UtilityComponents\Database;


use Edvardas\Hyphenation\App\App;

class MySqlQueryBuilder
{
    private $queryString = '';
    private $bindParams = [];
    private $counter = 0; //for prepared statement parameters

    public function build(): MySqlQuery
    {
        $query = new MySqlQuery($this->queryString, $this->bindParams);
        $this->reset();
        return $query;
    }

    public function select()
    {
        $this->queryString = $this->queryString . 'SELECT ';
        return $this;
    }

    /**
     * @param string[] $columns
     */
    public function columns(array $columns)
    {
        $columnsString = implode(', ', $columns);
        $this->queryString = $this->queryString . "$columnsString ";
        return $this;
    }

    public function from(string $table)
    {
        $this->queryString = $this->queryString . "FROM $table ";
        return $this;
    }

    public function join(string $table, string $joinColumnLeft, string $joinColumnRight)
    {
        $this->queryString = $this->queryString . "INNER JOIN $table ON $joinColumnLeft = $joinColumnRight ";
        return $this;
    }

    public function where()
    {
        $this->queryString = $this->queryString . 'WHERE ';
        return $this;
    }

    public function equals(string $column, string $value)
    {
        $nextCounter = $this->nextCounter();
        $paramName = ":value$nextCounter";
        $this->queryString = $this->queryString . "$column=$paramName ";
        $this->bindParams[$paramName] = $value;
        return $this;
    }

    public function and()
    {
        $this->queryString = $this->queryString . 'AND ';
        return $this;
    }

    public function in(string $column, array $values)
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

    public function insert()
    {
        $this->queryString = $this->queryString . 'INSERT ';
        return $this;
    }

    public function into(string $table, array $columns)
    {
        $columnsString = '(' . implode(', ', $columns) . ')';
        $this->queryString = $this->queryString . "INTO $table$columnsString ";
        return $this;
    }

    public function values(array $valuesMatrix)
    {
        $namesMatrix = $this->createNamesMatrix($valuesMatrix);
        $this->prepareBindParams($namesMatrix, $valuesMatrix);
        $insertRowsArray = array_map(function ($namesArray) {
            return '(' . implode(',', $namesArray) . ')';
        }, $namesMatrix);
        $insertNamesString = implode(',', $insertRowsArray);
        $this->queryString = $this->queryString . "VALUES $insertNamesString ";
        return $this;
    }

    public function delete()
    {
        $this->queryString = $this->queryString . 'DELETE ';
        return $this;
    }

    private function nextCounter()
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

    private function reset()
    {
        $this->queryString = '';
        $this->bindParams = [];
        $this->counter = 0;
    }

    private function createNamesMatrix($valuesMatrix)
    {
        $namesMatrix = [];
        foreach ($valuesMatrix as $valuesArray) {
            $namesArray = array_map(function ($val) {
                $nextCounter = $this->nextCounter();
                $name = ":value$nextCounter";
                return $name;
            }, $valuesArray);
            array_push($namesMatrix, $namesArray);
        }
        return $namesMatrix;
    }

    private function prepareBindParams(array $namesMatrix, array $valuesMatrix)
    {
        foreach ($namesMatrix as $rowIndex => $namesArray) {
            $valuesArray = $valuesMatrix[$rowIndex];
            $this->bindParams = array_merge($this->bindParams, array_combine($namesArray, $valuesArray));
        }
    }
}