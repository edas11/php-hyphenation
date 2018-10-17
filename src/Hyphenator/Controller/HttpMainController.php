<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 16.43
 */

namespace Edvardas\Hyphenation\Hyphenator\Controller;

use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Action\BadRequestAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordDeleteAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordsGetKnownAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordsHyphenationWithDbAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordPutAction;
use Edvardas\Hyphenation\Hyphenator\Input\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationHttpDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Route;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;

class HttpMainController implements Controller
{
    private $provider;
    private $appController;
    private $router;

    public function __construct(HyphenationHttpDataProvider $provider, HttpRequest $request)
    {
        $this->provider = $provider;
        $this->router = new Router($request->getRoute());
        $this->appController = new HttpAppController($provider, $this->router->getMatchedRoute(), $request->getBody());
    }

    public function getAction(): Action
    {
        $handlerName = $this->router->getRouteHandlerName();
        if ($handlerName === '') {
            return new BadRequestAction($this->provider);
        }
        return $this->appController->{$handlerName}();
    }
}