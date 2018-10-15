<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.9
 * Time: 09.35
 */

namespace Edvardas\Hyphenation\UtilityComponents\Database;


class MySqlQuery implements SqlQuery
{
    private $queryString;
    private $bindParams;

    public function __construct(string $queryString, array $bindParams)
    {
        $this->queryString = $queryString;
        $this->bindParams = $bindParams;
    }

    public function getQueryString(): string
    {
        return $this->queryString;
    }

    public function getBindParams(): array
    {
        return $this->bindParams;
    }
}