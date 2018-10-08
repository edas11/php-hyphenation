<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.3
 * Time: 14.15
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Algorithm;

interface HyphenationAlgorithmInterface
{
    public function execute(string $inputWord): string;
}
