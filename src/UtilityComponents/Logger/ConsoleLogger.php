<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 11.50
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\UtilityComponents\Logger;

use Edvardas\Hyphenation\UtilityComponents\Logger\AbstractLogger;

class ConsoleLogger extends AbstractLogger
{
    public function alert($message, array $context = array())
    {
        echo "Alert: $message\n";
    }

    public function critical($message, array $context = array())
    {
        echo "Critical: $message\n";
    }

    public function error($message, array $context = array())
    {
        echo "Error: $message\n";
    }

    public function warning($message, array $context = array())
    {
        echo "Warning: $message\n";
    }

    public function notice($message, array $context = array())
    {
        echo "Notice: $message\n";
    }

    public function info($message, array $context = array())
    {
        echo "Info: $message\n";
    }

    public function debug($message, array $context = array())
    {
        echo "Debug: $message\n";
    }

    public function emergency($message, array $context = array())
    {
        echo "Emergency: $message\n";
    }
}