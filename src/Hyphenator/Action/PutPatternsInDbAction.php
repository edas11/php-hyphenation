<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.35
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;


use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\UtilityComponents\Input\ConsoleInput;
use Edvardas\Hyphenation\UtilityComponents\Output\ConsoleOutput;

class PutPatternsInDbAction implements Action
{
    private $config;
    private $input;
    private $output;

    public function __construct($config)
    {
        $this->config = $config;
        $this->input = new ConsoleInput();
        $this->output = new ConsoleOutput();
    }

    public function execute()
    {
        $this->dialogLoadPatterns();
        $db = new HyphenationDatabase();
        $db->putPatternsInDB($this->loadPatterns());
    }

    private function dialogLoadPatterns()
    {
        $this->output->printLn("Loading patterns");
    }

    private function loadPatterns(): array
    {
        $patternsFileName = $this->config->get('patternsFileName', 'patterns');
        $patterns = file($patternsFileName, FILE_IGNORE_NEW_LINES);
        if ($patterns === false) {
            App::$logger->error("Could not read patterns file.");
            exit;
        }
        return $patterns;
    }
}