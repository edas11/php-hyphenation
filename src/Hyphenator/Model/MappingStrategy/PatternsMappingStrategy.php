<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.16
 * Time: 10.46
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Model\MappingStrategy;

use Edvardas\Hyphenation\UtilityComponents\Database\DbDataMappingStrategy;

class PatternsMappingStrategy implements DbDataMappingStrategy
{
    private $offset;

    public function __construct(int $offset = 0)
    {
        $this->offset = $offset;
    }

    public function map(array $dbData): array
    {
        $patterns = array_column($dbData, 'pattern');
        $nrs = range(1 + $this->offset, count($patterns) + $this->offset);
        return array_combine($nrs, $patterns);
    }
}