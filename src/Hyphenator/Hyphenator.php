<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.12
 * Time: 17.11
 */

namespace Edvardas\Hyphenation\Hyphenator;

interface Hyphenator
{
    public function execute(): void;
}