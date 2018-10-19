<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 12.31
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\UtilityComponents\Http;

class MatchedRoute
{
    private $queryParams = [];
    private $pathParam;
    private $matches;

    public function __construct(bool $matches, string $pathParam = '', array $queryParams = [])
    {
        $this->queryParams = $queryParams;
        $this->pathParam = $pathParam;
        $this->matches = $matches;
    }

    public function matches(): bool
    {
        return $this->matches;
    }

    public function getPathParam(): string
    {
        return $this->pathParam;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }
}