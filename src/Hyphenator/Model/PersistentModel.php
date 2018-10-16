<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.10
 * Time: 09.09
 */

namespace Edvardas\Hyphenation\Hyphenator\Model;


interface PersistentModel
{
    public function persist(): void;
}