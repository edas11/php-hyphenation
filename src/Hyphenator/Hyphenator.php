<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.05
 */

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\UtilityComponents\Config\Config;

class Hyphenator
{
    private $provider;

    public function __construct(Config $config)
    {
        $this->provider = new HyphenationActionProvider($config);
    }

    public function hyphenateWords() {
        $action = $this->provider->getAction();
        return $action->execute();
    }
}