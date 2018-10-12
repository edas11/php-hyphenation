<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.05
 */

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\Hyphenator\Controller\HttpController;
use Edvardas\Hyphenation\Hyphenator\Input\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Output\JsonHyphenationOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationConsoleDataProvider;

class WebHyphenator
{
    private $output;
    private $controller;

    public function __construct()
    {
        header('content-type: application/json');
        $this->output = new JsonHyphenationOutput();
        $this->controller = new HttpController($this->output);
    }

    public function execute(): void {
        $action = $this->controller->getAction();
        $action->execute();
        $this->output->flush();
    }
}