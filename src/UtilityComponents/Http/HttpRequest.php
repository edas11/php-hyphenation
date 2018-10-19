<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 12.00
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\UtilityComponents\Http;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\UtilityComponents\Http\Route;

class HttpRequest
{
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function parseRoute(): Route
    {
        return new Route($_SERVER['REQUEST_URI']);
    }

    public function parseBody(): HttpBody
    {
        if (!array_key_exists("CONTENT_TYPE", $_SERVER)){
            return new HttpBody([]);
        }
        $contentType = $_SERVER["CONTENT_TYPE"];
        if ($contentType === 'application/json') {
            $body = file_get_contents('php://input');
            $bodyArray = json_decode($body, true);
            if (!is_array($bodyArray)) {
                $bodyArray = [];
            }
        } elseif ($contentType === 'application/x-www-form-urlencoded' || $contentType === 'multipart/form-data') {
            $bodyArray = $_POST;
        } else {
            throw new \Exception('Unsupported body content type');
        }
        return new HttpBody($bodyArray);
    }
}