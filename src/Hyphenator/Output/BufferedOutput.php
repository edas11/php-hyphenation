<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.19
 * Time: 10.50
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Output;

interface BufferedOutput
{
    public function set(string $key, $data): void;
    public function flush(): void;
}