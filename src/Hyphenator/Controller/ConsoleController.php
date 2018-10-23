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
use Edvardas\Hyphenation\Hyphenator\Output\ConsoleOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationConsoleDataProvider;
use Edvardas\Hyphenation\Hyphenator\Providers\ConsoleDataProviderFactory;

class ConsoleController implements Controller
{
    private $provider;
    private $inputData;
    private $output;

    public function __construct(InputDialog $input, ConsoleDataProviderFactory $factory, ConsoleOutput $output)
    {
        $this->inputData = $input->getConsoleInput();
        $this->provider = $factory->build();
        $this->output = $output;
    }

    public function handleRequest(): void
    {
        $actionName = $this->inputData->getActionName();
        if (class_exists($actionName)) {
            $action = new $actionName($this->provider, $this->output);
            $action->execute();
        }
    }
}