<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.25
 * Time: 15.46
 */

namespace Edvardas\Hyphenation\Hyphenator\Controller\WebControllers;


use Edvardas\Hyphenation\Hyphenator\Controller\Controller;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInputBuilder;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;
use Psr\Log\LoggerInterface;

class ApiOptionsController implements Controller
{
    private $output;

    public function __construct(
        HyphenationInputBuilder $modelInputBuilder,
        HttpRequest $request,
        Router $router,
        WebOutput $output,
        ModelFactory $modelFactory,
        LoggerInterface $logger
    ) {
        $this->output = $output;
    }

    public function handleRequest(): void
    {
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
        $this->output->configureOutput('application/json');
    }
}