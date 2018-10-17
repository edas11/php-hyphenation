<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.05
 */

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\Hyphenator\Controller\HttpMainController;
use Edvardas\Hyphenation\Hyphenator\Input\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Output\JsonHyphenationOutput;

class WebHyphenator implements Hyphenator
{
    private $controller;
    private $output;

    public function __construct(HttpMainController $controller, JsonHyphenationOutput $output)
    {
        header('content-type: application/json');
        $this->controller = $controller;
        $this->output = $output;
    }

    public function execute(): void {
        $action = $this->controller->getAction();
        $action->execute();
        $this->output->flush();
    }
}