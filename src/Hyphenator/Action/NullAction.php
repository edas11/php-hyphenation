<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.18
 * Time: 13.45
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Action;

class NullAction implements Action
{
    public function execute(): void
    {
    }
}