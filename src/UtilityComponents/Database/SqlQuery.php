<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.15
 * Time: 15.54
 */

namespace Edvardas\Hyphenation\UtilityComponents\Database;

interface SqlQuery
{
    public function getQueryString(): string;

    public function getBindParams(): array;
}