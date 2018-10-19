<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.05
 */

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\Hyphenator\Controller\ConsoleController;
use Edvardas\Hyphenation\Hyphenator\Output\ConsoleOutput;

class ConsoleHyphenator implements Hyphenator
{
    private $controller;
    private $output;

    public function __construct(ConsoleController $controller, ConsoleOutput $output)
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