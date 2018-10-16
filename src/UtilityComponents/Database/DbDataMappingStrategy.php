<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.16
 * Time: 10.45
 */

namespace Edvardas\Hyphenation\UtilityComponents\Database;


interface DbDataMappingStrategy
{
    public function map(array $dbData): array;
}