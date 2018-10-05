<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 11.50
 */

namespace Edvardas\Hyphenation\UtilityComponents\Logger;

use Edvardas\Hyphenation\UtilityComponents\Logger\AbstractLogger;

class FileLogger extends AbstractLogger
{

    private $logFile;

    public function __construct()
    {
        $logFile = fopen('log', 'ab');
        if ($logFile === false) {
            throw new \Exception('Can not open/create log file');
        }
        $this->logFile = $logFile;
    }

    public function __destruct()
    {
        $this->writeToLogFile("\n");
    }

    public function alert($message, array $context = array())
    {
        $this->writeToLogFile("Alert: $message\n");
    }

    public function critical($message, array $context = array())
    {
        $this->writeToLogFile("Critical: $message\n");
    }

    public function error($message, array $context = array())
    {
        $this->writeToLogFile("Error: $message\n");
    }

    public function warning($message, array $context = array())
    {
        $this->writeToLogFile("Warning: $message\n");
    }

    public function notice($message, array $context = array())
    {
        $this->writeToLogFile("Notice: $message\n");
    }

    public function info($message, array $context = array())
    {
        $this->writeToLogFile("Info: $message\n");
    }

    public function debug($message, array $context = array())
    {
        $this->writeToLogFile("Debug: $message\n");
    }

    public function emergency($message, array $context = array())
    {
        $this->writeToLogFile("Emergency: $message\n");
    }

    private function writeToLogFile(string $message)
    {
        $status = fwrite($this->logFile, $message);
        if ($status === false) {
            throw new \Exception('Can not write to log file');
        }
    }
}