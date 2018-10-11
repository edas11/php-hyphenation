<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.05
 */

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\Hyphenator\Input\ConsoleInput;
use Edvardas\Hyphenation\Hyphenator\Output\ConsoleOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class ConsoleHyphenator
{
    private $provider;

    public function __construct()
    {
        $this->provider = new HyphenationDataProvider(new ConsoleInput(), new ConsoleOutput());
    }

    public function execute(): void {
        $action = $this->provider->getAction();
        $action->execute();
    }
}