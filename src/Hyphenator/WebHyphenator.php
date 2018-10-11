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
    private $output;

    public function __construct()
    {
        header('content-type: application/json');
        $this->output = new JsonHyphenationOutput();
        $this->provider = new HyphenationDataProvider(new HttpInput(), $this->output);
    }

    public function execute(): void {
        $action = $this->provider->getAction();
        $action->execute();
        $this->output->flush();
    }
}