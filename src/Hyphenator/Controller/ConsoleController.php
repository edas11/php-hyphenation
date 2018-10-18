<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 16.41
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Controller;

use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Console\InputDialog;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationConsoleDataProvider;
use Edvardas\Hyphenation\Hyphenator\Providers\ConsoleDataProviderFactory;

class ConsoleController implements Controller
{
    private $provider;
    private $inputData;

    public function __construct(InputDialog $input, ConsoleDataProviderFactory $factory)
    {
        $this->inputData = $input->getConsoleInput();
        $this->provider = $factory->build();
    }

    public function getAction(): Action
    {
        $actionName = $this->inputData->getActionName();
        if (class_exists($actionName)) {
            return new $actionName($this->provider);
        }
    }
}