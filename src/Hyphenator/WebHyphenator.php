<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.05
 */

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\Hyphenator\Controller\HttpController;
use Edvardas\Hyphenation\Hyphenator\Console\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;

class WebHyphenator implements Hyphenator
{
    private $controller;
    private $output;

    public function __construct(HttpController $controller, WebOutput $output)
    {
        $this->controller = $controller;
        $this->output = $output;
    }

    public function execute(): void {
        $action = $this->controller->getAction();
        $action->execute();
        $this->output->flush();
    }
}