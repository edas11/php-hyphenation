<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.26
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\UtilityComponents\Config;

class Config
{
    private $config = [];

    public function __construct(array $configData)
    {
        array_walk_recursive($configData, function ($item) {
            if (!is_string($item)) {
                throw new \Exception('Config must have only strings');
            }
        });
        $this->config = $configData;
    }

    /**
     * @param string[] $keys
     */
    public function get(array $keys, string $default = ''): string
    {
        $configData = $this->config;
        foreach ($keys as $keyPart) {
            if (array_key_exists($keyPart, $configData)) {
                $configData = $configData[$keyPart];
            } else {
                return $default;
            }
        }

        if (is_string($configData)) {
            return $configData;
        } else {
            return $default;
        }
    }
}