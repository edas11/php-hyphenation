<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 11.41
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Controller\WebControllers;

use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\ModelAction\NullAction;
use Edvardas\Hyphenation\Hyphenator\Controller\Controller;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInputBuilder;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;
use Psr\Log\LoggerInterface;

class PageHyphenateWordsController implements Controller
{
    private $modelInputBuilder;
    private $body;
    private $output;

    public function __construct(
        HyphenationInputBuilder $modelInputBuilder,
        HttpRequest $request,
        Router $router,
        WebOutput $output,
        ModelFactory $modelFactory,
        LoggerInterface $logger
    ) {
        $this->body = $request->parseBody();
        $this->modelInputBuilder = $modelInputBuilder;
        $this->output = $output;
    }

    public function handleRequest(): void
    {
        $this->output->configureOutput('text/html', 'views/pages/hyphenateWordsPage.php');
    }
}