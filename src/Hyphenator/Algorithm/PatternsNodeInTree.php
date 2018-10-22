<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.3
 * Time: 13.10
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Algorithm;

class PatternsNodeInTree
{
    private $patterns = [];

    public function add(string $pattern)
    {
        $this->patterns[] = $pattern;
    }

    public function addAll(array $patterns)
    {
        $this->patterns = array_merge($this->patterns, $patterns);
    }

    public function get()
    {
        return $this->patterns;
    }
}
