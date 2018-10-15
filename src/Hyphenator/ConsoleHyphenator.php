<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.05
 */

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\Hyphenator\Controller\ConsoleController;

class ConsoleHyphenator implements Hyphenator
{
    private $controller;

    public function __construct(ConsoleController $controller)
    {
        $this->controller = $controller;
    }

    public function execute(): void {
        $action = $this->controller->getAction();
        $action->execute();
    }
}