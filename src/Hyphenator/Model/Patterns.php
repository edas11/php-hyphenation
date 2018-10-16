<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.9
 * Time: 16.32
 */
declare(strict_types = 1);
namespace Edvardas\Hyphenation\Hyphenator\Model;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Model\MappingStrategy\PatternsMappingStrategy;

class Patterns implements PersistentModel
{
    private $patterns = [];

    /**
     * @param string[][] $patterns
     */
    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
    }

    public function getPatterns()
    {
        return $this->patterns;
    }

    public static function getKnown(): Patterns
    {
        $db = App::getDb();
        $builder = $db->builder();
        $query = $builder
            ->select()
            ->columns(['pattern'])
            ->from('patterns')
            ->build();
        $db->beginTransaction();
        $patterns = $db->executeAndFetch($query, new PatternsMappingStrategy());
        $db->commit();
        return new Patterns($patterns);
    }

    public function persist(): void
    {
        $db = App::getDb();
        $db->beginTransaction();
        $this->persistNoTransaction();
        $db->commit();
    }

    public function persistNoTransaction(): void
    {
        $db = App::getDb();
        $builder = $db->builder();
        $query = $builder
            ->delete()
            ->from('patterns')
            ->build();
        $db->execute($query);
        $builder = $builder
            ->insert()
            ->into('patterns', ['pattern']);
        foreach ($this->patterns as $pattern) {
            $builder = $builder->values([$pattern]);
        }
        $query = $builder->build();
        $db->execute($query);
    }
}