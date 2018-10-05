<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 13.53
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\UtilityComponents\Cache;

use Edvardas\Hyphenation\UtilityComponents\Cache\UnsupportedOperationException;
use Psr\SimpleCache\CacheInterface;

class MemoryCache implements CacheInterface
{
    private $cacheArray;

    /*
     * @param string $key
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->cacheArray[$key];
        } else {
            return $default;
        }
    }

    /*
     * @param string $key
     */
    public function set($key, $value, $ttl = null): bool
    {
        $this->cacheArray[$key] = $value;
        return true;
    }

    /*
     * @param string $key
     */
    public function delete($key): bool
    {
        unset($this->cacheArray[$key]);
        return true;
    }

    public function clear(): bool
    {
        $this->cacheArray = [];
        return true;
    }

    public function getMultiple($keys, $default = null)
    {
        throw new UnsupportedOperationException();
    }

    public function setMultiple($values, $ttl = null)
    {
        throw new UnsupportedOperationException();
    }

    public function deleteMultiple($keys)
    {
        throw new UnsupportedOperationException();
    }

    /*
    * @param string $key
    */
    public function has($key): bool
    {
        return array_key_exists($key, $this->cacheArray);
    }

}