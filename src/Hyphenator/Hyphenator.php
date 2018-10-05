<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.05
 */

namespace Edvardas\Hyphenation\Hyphenator;


use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\UtilityComponents\Config\Config;
use Edvardas\Hyphenation\UtilityComponents\Input\ConsoleInput;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;

class Hyphenator
{
    private $config;
    private $input;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->input = new ConsoleInput();
    }

    public function hyphenateWords() {
        $patterns = $this->loadPatterns();
        $inputWords = $this->getWords();
        $this->turnOffLoggerIfMoreWordsThanThreshold($inputWords);
        $algorithm = $this->getAlgorithm($patterns);

        $result = [];
        foreach ($inputWords as $inputWord) {
            array_push($result, $algorithm->execute($inputWord));
        }
        return $result;
    }

    public function loadPatterns(): array
    {
        $patternsFileName = $this->config->get('patternsFileName', 'patterns');
        $patterns = file($patternsFileName, FILE_IGNORE_NEW_LINES);
        if ($patterns === false) {
            App::$logger->error("Could not read patterns file.");
            exit;
        }
        return $patterns;
    }

    public function turnOffLoggerIfMoreWordsThanThreshold(array $inputWords): void
    {
        if (count($inputWords) > App::WORDS_THRESHOLD) {
            App::$logger->notice('Too many words, disabling logger.');
            App::$logger = new NullLogger();
        }
    }

    public function getWords(): array
    {
        $wordsFileName = $this->config->get('wordsFileName', 'words.txt');
        $arguments = $this->input->getArguments();
        if (count($arguments) > 0) {
            foreach ($arguments as $i => $argument) {
                $inputWords[$i] = $argument;
            }
        } else {
            App::$logger->info("Reading words from $wordsFileName file.");
            $inputWords = file($wordsFileName, FILE_IGNORE_NEW_LINES);
            if ($inputWords === false) {
                App::$logger->error("Could not read $wordsFileName file.");
                exit;
            }
        }
        return $inputWords;
    }

    public function getAlgorithm($patterns): HyphenationAlgorithmInterface
    {
        $options = $this->input->getOptions();
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

}