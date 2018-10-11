<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 10.31
 */

namespace Edvardas\Hyphenation\Hyphenator\Input;


class HttpInput implements HyphenationInput
{
    public function getActionInput(): int
    {
        return InputCodes::HYPHENATE_ACTION;
    }

    public function getSourceInput(): int
    {
        return InputCodes::FILE_SRC;
    }

    public function getWordsInput(): string
    {
        return 'mistranslate';
    }

    public function getAlgorithmInput(): int
    {
        return InputCodes::FULL_TREE_ALGORITHM;
    }

}