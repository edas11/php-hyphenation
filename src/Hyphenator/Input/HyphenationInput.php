<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 10.03
 */

namespace Edvardas\Hyphenation\Hyphenator\Input;

interface HyphenationInput
{
    public function getActionInput(): int;

    public function getSourceInput(): int;

    public function getWordsInput(): string;

    public function getAlgorithmInput(): int;
}