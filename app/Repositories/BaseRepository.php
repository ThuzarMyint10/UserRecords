<?php

namespace App\Repositories;

use App\Db\Core\Crud;
use App\Helper\ReadOnlyArray;
use App\Contracts\BaseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class BaseRepository implements BaseInterface
{

  public function getModelInstance(string $model): Model
  {
    return app("App\Models\\{$model}");
  }

  public function all(string $model)
  {
    $this->getModelInstance($model)->all();
  }

  public function store(string $model, array $data, string $filePath = null, string $tableName = null, string $diskName = null)
  {
    new ReadOnlyArray($data);
    $crud = new Crud(model: $this->getModelInstance($model), data: $data, storeMode: true);
    if (get_class($this->getModelInstance($model)) !== Config::get('variables.IMAGE_MODEL')) {
      return $crud->execute();
    }
    $crud->setImageDirectory($filePath, $tableName, $diskName);
    return $crud->execute();
  }

  public function twoModelsStore(string $model, int $id, string $relation, array $data)
  {
    return (new Crud(model: $this->getModelInstance($model), data: $data, id: $id, relation: $relation, twoModelsStoreMode: true))->execute();
  }

  public function update(string $model, array $data, int $id)
  {
    new ReadOnlyArray($data);
    return (new Crud(model: $this->getModelInstance($model), data: $data, id: $id, editMode: true))->execute();
  }

  public function delete(string $model, int $id)
  {
    return (new Crud(model: $this->getModelInstance($model), id: $id,  deleteMode: true))->execute();
  }
}
