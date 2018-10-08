<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.58
 */

namespace Edvardas\Hyphenation\Hyphenator;


use Edvardas\Hyphenation\UtilityComponents\Input\ConsoleInput;
use Edvardas\Hyphenation\UtilityComponents\Output\ConsoleOutput;

class HyphenationDataProvider
{
    private $input;
    private $output;

    public function __construct($config)
    {
        $this->config = $config;
        $this->input = new ConsoleInput();
        $this->output = new ConsoleOutput();
    }

    public function getActionInput()
    {
        $this->output->printLn("Started hyphenation algorithm.");
        $this->output->printLn("Choose action:");
        $this->output->printLn("(1) Hyphenate words");
        $this->output->printLn("(2) Load patterns to database");
        $choice = (int)$this->input->getInput();
        return $choice;
    }
}