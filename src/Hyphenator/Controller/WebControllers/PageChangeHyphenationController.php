<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 11.41
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Controller\WebControllers;

use Edvardas\Hyphenation\Hyphenator\Action\HyphenationAction;
use Edvardas\Hyphenation\Hyphenator\Action\NullHyphenationAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordPutHyphenationAction;
use Edvardas\Hyphenation\Hyphenator\Controller\Controller;
use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HttpDataProviderFactory;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;

class PageChangeHyphenationController implements Controller
{
    private $matchedRoute;
    private $factory;
    private $body;

    public function __construct(
        HttpDataProviderFactory $factory,
        HttpRequest $request,
        Router $router,
        WebOutput $output
    ) {
        $this->matchedRoute = $router->getMatchedRoute();
        $this->body = $request->parseBody();
        $this->factory = $factory;
        $this->output = $output;
    }

    public function getAction(): HyphenationAction
    {
        $this->output->configureOutput('text/html', 'pages/changeHyphenationPage.php');
        $queryParams = $this->matchedRoute->getQueryParams();
        if (array_key_exists('for', $queryParams) && $queryParams['for'] !== '') {
            $this->output->set('for', $queryParams['for']);
        }
        return new NullHyphenationAction();
    }
}