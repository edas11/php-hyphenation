<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.9
 * Time: 15.43
 */

namespace Edvardas\Hyphenation\Hyphenator\Model;

interface DatabaseModel
{
    public function persist(): void;
    public static function get();
}