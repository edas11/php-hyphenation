<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 09.36
 */

namespace Edvardas\Hyphenation\App;

use Edvardas\Hyphenation\Hyphenator\ConsoleHyphenator;
use Edvardas\Hyphenation\Hyphenator\DiContainer\DiContainer;
use Edvardas\Hyphenation\Hyphenator\Hyphenator;
use Edvardas\Hyphenation\Hyphenator\WebHyphenator;

class App
{
    /**
     * @var Hyphenator
     */
    private $hyphenator;

    public function executeCommand()
    {
        $container = new DiContainer();
        if (php_sapi_name() === 'cli') {
            $this->hyphenator = $container->get(ConsoleHyphenator::class);
        } else {
            $this->hyphenator = $container->get(WebHyphenator::class);
        }
        $this->hyphenator->execute();

    }
}
