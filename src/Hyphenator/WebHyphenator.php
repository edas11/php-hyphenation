<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.05
 */

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\Hyphenator\Input\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Output\JsonHyphenationOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class WebHyphenator
{
    private $provider;

    public function __construct()
    {
        header('content-type: application/json');
        $this->provider = new HyphenationDataProvider(new HttpInput(), new JsonHyphenationOutput());
    }

    public function execute(): void {
        $action = $this->provider->getAction();
        $action->execute();
    }
}