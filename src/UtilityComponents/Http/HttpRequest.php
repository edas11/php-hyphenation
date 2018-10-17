<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 12.00
 */

namespace Edvardas\Hyphenation\UtilityComponents\Http;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\UtilityComponents\Http\Route;

class HttpRequest
{
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getRoute(): Route
    {
        return new Route($_SERVER['REQUEST_URI']);
    }

    public function getBody(): array
    {
        $body = file_get_contents('php://input');
        $jsonBody = json_decode($body, true);
        if (is_array($jsonBody)) {
            return $jsonBody;
        }
        return [];
    }
}