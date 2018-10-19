<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.24
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;

interface HyphenationAction
{
    public function execute(): void;
}