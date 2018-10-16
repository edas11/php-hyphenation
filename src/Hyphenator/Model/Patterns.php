<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.9
 * Time: 16.32
 */
declare(strict_types = 1);
namespace Edvardas\Hyphenation\Hyphenator\Model;

use Edvardas\Hyphenation\Hyphenator\Model\MappingStrategy\PatternsMappingStrategy;
use Edvardas\Hyphenation\UtilityComponents\Database\SqlDatabase;

class Patterns implements PersistentModel
{
    private $patterns = [];
    private $db;

    /**
     * @param string[][] $patterns
     */
    public function __construct(array $patterns, SqlDatabase $db)
    {
        $this->patterns = $patterns;
        $this->db = $db;
    }

    public function getPatterns()
    {
        return $this->patterns;
    }

    public static function getKnown(SqlDatabase $db): Patterns
    {
        $builder = $db->builder();
        $query = $builder
            ->select()
            ->columns(['pattern'])
            ->from('patterns')
            ->build();
        $token = $db->beginTransaction();
        $patterns = $db->executeAndFetch($query, new PatternsMappingStrategy());
        $db->commit($token);
        return new Patterns($patterns, $db);
    }

    public function persist(): void
    {
        $token = $this->db->beginTransaction();
        $builder = $this->db->builder();
        $query = $builder
            ->delete()
            ->from(HyphenatedWords::WORDS_TABLE)
            ->build();
        $this->db->execute($query);
        $builder = $builder
            ->replace()
            ->into('patterns', ['pattern']);
        foreach ($this->patterns as $pattern) {
            $builder->values([$pattern]);
        }
        $query = $builder->build();
        $this->db->execute($query);
        $this->db->commit($token);
    }
}