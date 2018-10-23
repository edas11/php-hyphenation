<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.35
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\ModelAction;

use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;
use Psr\Log\LoggerInterface;

class PatternsSaveInDbModelAction implements ModelAction
{
    private $modelFactory;
    private $patternsInput;

    public function __construct(
        HyphenationInput $modelInput,
        BufferedOutput $output,
        ModelFactory $modelFactory,
        LoggerInterface $logger
    ) {
        $this->modelFactory = $modelFactory;
        $this->patternsInput = $modelInput->getPatternsInput();
    }

    public function execute(): void
    {
        $this->modelFactory->createHyphenatedWords([])->deleteAll();
        $this->modelFactory->createPatternsModel($this->patternsInput)->persist();
    }

}