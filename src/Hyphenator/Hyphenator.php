<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.05
 */

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationConsoleDataProvider;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationWebApiDataProvider;

class Hyphenator
{
    private $provider;

    public function __construct()
    {
        if (php_sapi_name() === 'cli') {
            $this->provider = new HyphenationConsoleDataProvider();
        } else {
            header('content-type: application/json');
            $this->provider = new HyphenationWebApiDataProvider();
            /*echo json_encode('string');
            flush();
            exit();*/
        }
    }

    public function execute(): void {
        $action = $this->provider->getAction();
        $action->execute();
    }
}