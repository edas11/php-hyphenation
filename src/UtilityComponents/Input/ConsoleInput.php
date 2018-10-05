<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 16.36
 */

namespace Edvardas\Hyphenation\UtilityComponents\Input;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\HyphenationAlgorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\HyphenationAlgorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\HyphenationAlgorithm\ShortTreeHyphenationAlgorithm;

class ConsoleInput
{
    private $cliArguments;

    public function __construct()
    {
        global $argv;
        $this->cliArguments = $argv;
    }

    public function getWords(): array
    {
        $arguments = $this->getArguments();
        if (count($arguments) > 0) {
            foreach($arguments as $i => $argument) {
                $inputWords[$i] = $argument;
            }
        } else {
            App::$logger->info("Reading words from words.txt file.");
            $inputWords = file('words.txt', FILE_IGNORE_NEW_LINES);
            if ($inputWords === false) {
                App::$logger->error("Could not read words.txt file.");
                exit;
            }
        }
        return $inputWords;
    }

    public function getAlgorithm($patterns): HyphenationAlgorithmInterface
    {
        $options = $this->getOptions();
        if (array_key_exists('type', $options)) {
            $typeOption = $options['type'];
        } else {
            $typeOption = 'full-tree';
        }

        switch ($typeOption) {
            case 'full-tree':
                return new FullTreeHyphenationAlgorithm($patterns);
                break;
            case 'short-tree':
                return new ShortTreeHyphenationAlgorithm($patterns);
                break;
            default:
                return new FullTreeHyphenationAlgorithm($patterns);
        }

    }

    private function getArguments(): array
    {
        $arguments = [];
        for ($i=1; $i < count($this->cliArguments); $i++) {
            if (strpos($this->cliArguments[$i], '--') !== 0) {
                array_push($arguments, $this->cliArguments[$i]);
            }
        }
        return $arguments;
    }

    private function getOptions(): array
    {
        $options = [];
        for ($i=1; $i < count($this->cliArguments); $i++) {
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
}