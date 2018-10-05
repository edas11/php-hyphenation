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
        foreach ($configData as $configKey => $configItem) {
            $this->config[(string)$configKey] = (string)$configItem;
        }
    }

    public function get(string $key, string $default = ''): string
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        } else {
            return $default;
        }
    }

    public function set(string $key, string $val): void
    {
        $this->config[$key] = $val;
    }
}