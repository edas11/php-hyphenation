<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 09.42
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\UtilityComponents\Console;

class Console
{
    private $cliArguments;

    public function __construct()
    {
        global $argv;
        $this->cliArguments = $argv;
    }

    public function getInput()
    {
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        fclose($handle);
        return trim($line);
    }

    public function getArguments(): array
    {
        $arguments = [];
        for ($i = 1; $i < count($this->cliArguments); $i++) {
            if (strpos($this->cliArguments[$i], '--') !== 0) {
                $arguments[] = $this->cliArguments[$i];
            }
        }
        return $arguments;
    }

    public function getOptions(): array
    {
        $options = [];
        for ($i = 1; $i < count($this->cliArguments); $i++) {
            if (strpos($this->cliArguments[$i], '--') === 0 && strlen($this->cliArguments[$i]) > 2) {
                $optionString = substr($this->cliArguments[$i], 2);
                $algorithmTypeOptionArray = explode('=', $optionString, 2);
                if (count($algorithmTypeOptionArray) === 1) {
                    $algorithmTypeOptionArray[1] = true;
                }
                $key = $algorithmTypeOptionArray[0];
                $value = $algorithmTypeOptionArray[1];
                $options[$key] = $value;
            }
        }
        return $options;
    }

    public function printLn(string $line)
    {
        echo "$line\n";
    }
}