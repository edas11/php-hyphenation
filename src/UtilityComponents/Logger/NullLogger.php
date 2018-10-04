<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 11.50
 */

namespace Edvardas\Hyphenation\UtilityComponents\Logger;

use Edvardas\Hyphenation\UtilityComponents\Logger\AbstractLogger;

class NullLogger extends AbstractLogger
{
    public function alert($message, array $context = array())
    {
    }

    public function critical($message, array $context = array())
    {
    }

    public function error($message, array $context = array())
    {
    }

    public function warning($message, array $context = array())
    {
    }

    public function notice($message, array $context = array())
    {
    }

    public function info($message, array $context = array())
    {
    }

    public function debug($message, array $context = array())
    {
    }

    public function emergency($message, array $context = array())
    {
    }
}