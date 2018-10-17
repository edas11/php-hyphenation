<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 13.56
 */
declare(strict_types = 1);
namespace Edvardas\Hyphenation\UtilityComponents\Http;


class HttpBody
{
    private $bodyData;

    public function __construct(array $bodyData)
    {
        $this->bodyData = $bodyData;
    }

    public function hasString(string $key): bool
    {
        return array_key_exists($key, $this->bodyData) && is_string($this->bodyData[$key]);
    }

    public function hasArray(string $key): bool
    {
        return array_key_exists($key, $this->bodyData) && is_array($this->bodyData[$key]);
    }

    public function get(string $key)
    {
        if (array_key_exists($key, $this->bodyData)) {
            return $this->bodyData[$key];
        } else {
            return null;
        }
    }
}