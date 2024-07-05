<?php

namespace App\Repositories;

use App\Db\Core\Crud;
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

  public function store(string $model, array $data, string $imageDir = null, string $tableName = null, string $diskName = null)
  {
    $crud = new Crud(model: $this->getModelInstance($model), data: $data, storeMode: true);
    if ($this->getModelInstance($model)->getTable() !== Config::get('variables.IMAGE_MODEL')) {
      return $crud->execute();
    }
    $crud->setImageDirectory($imageDir, $tableName, $diskName);
    return $crud->execute();
  }

  public function massDataSave(string $model, array $data)
  {
    $crud = new Crud(model: $this->getModelInstance($model), data: $data, massDataSavingMode: true);
    return $crud->execute();
  }

  public function twoModelsStore(string $model, int $id, string $relation, array $data)
  {
    return (new Crud(model: $this->getModelInstance($model), data: $data, id: $id, relation: $relation, twoModelsStoreMode: true))->execute();
  }

  public function update(string $model, array $data, int $id, string $imageDir = null, string $tableName = null, string $diskName = null)
  {
    $crud = new Crud(model: $this->getModelInstance($model), data: $data, id: $id, editMode: true);
    if ($this->getModelInstance($model)->getTable() !== Config::get('variables.IMAGE_MODEL')) {
      return $crud->execute();
    }
    $crud->setImageDirectory($imageDir, $tableName, $diskName);
    return $crud->execute();
  }

  public function delete(string $model, int $id)
  {
    return (new Crud(model: $this->getModelInstance($model), id: $id,  deleteMode: true))->execute();
  }
}
