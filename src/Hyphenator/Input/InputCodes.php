<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 10.56
 */

namespace Edvardas\Hyphenation\Hyphenator\Input;


class InputCodes
{
    public const HYPHENATE_ACTION = 1;
    public const PUT_PATTERNS_IN_DB_ACTION = 2;
    public const BAD_REQUEST_ACTION = 400;
    public const GET_KNOWN_WORDS_ACTION = 200;
    public const PUT_WORD_ACTION = 201;
    public const DELETE_WORD_ACTION = 202;

    public const FILE_SRC = 1;
    public const DB_SRC = 2;

    public const FULL_TREE_ALGORITHM = 1;
    public const SHORT_TREE_ALGORITHM = 2;
}