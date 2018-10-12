<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.10
 * Time: 09.08
 */

namespace Edvardas\Hyphenation\Hyphenator\Model;


use Edvardas\Hyphenation\App\App;

class CompositeModel implements PersistentModel
{
    private $models;

    /**
     * @param PersistentModel[] $models
     */
    public function __construct(array $models)
    {
        $this->models = $models;
    }

    public function persist(): void
    {
        $db = App::getDb();
        $db->beginTransaction();
        foreach ($this->models as $model) {
            $model->persistNoTransaction();
        }
        $db->commit();
    }

    public function persistNoTransaction(): void
    {
        throw new \Exception('Unsupported operation');
    }

}