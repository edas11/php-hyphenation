<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 13.58
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\UtilityComponents\Cache;

use Psr\SimpleCache\CacheException;

class UnsupportedOperationException extends \Exception implements CacheException
{

}