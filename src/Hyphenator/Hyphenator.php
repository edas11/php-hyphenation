<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.05
 */

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationActionProvider;

class Hyphenator
{
    private $provider;

    public function __construct()
    {
        $this->provider = new HyphenationActionProvider;
    }

    public function execute(): void {
        $action = $this->provider->getAction();
        $action->execute();
    }
}