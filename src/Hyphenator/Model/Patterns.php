<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.9
 * Time: 16.32
 */

namespace Edvardas\Hyphenation\Hyphenator\Model;

use Edvardas\Hyphenation\App\App;

class Patterns implements PersistentModel
{
    private $patterns = [];

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
    }

    public function getPatterns()
    {
        return array_column($this->patterns, 'pattern');
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
        $patterns = $db->executeAndFetch($query);
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
        $query = $builder
            ->insert()
            ->into('patterns', ['pattern'])
            ->values($this->patterns)->build();
        $db->execute($query);
    }
}