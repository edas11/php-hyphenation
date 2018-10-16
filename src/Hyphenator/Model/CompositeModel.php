<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.10
 * Time: 09.08
 */

namespace Edvardas\Hyphenation\Hyphenator\Model;

use Edvardas\Hyphenation\UtilityComponents\Database\SqlDatabase;

class CompositeModel implements PersistentModel
{
    private $models;
    private $db;

    /**
     * @param PersistentModel[] $models
     */
    public function __construct(array $models, SqlDatabase $db)
    {
        $this->models = $models;
        $this->db = $db;
    }

    public function persist(): void
    {
        $token = $this->db->beginTransaction();
        foreach ($this->models as $model) {
            $model->persist();
        }
        $this->db->commit($token);
    }
}