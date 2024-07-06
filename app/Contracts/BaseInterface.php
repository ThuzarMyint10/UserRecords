<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface BaseInterface
{
  public function all(string $model);
  public function store(string $model, array $data, string $imageDir = null, string $tableName = null, string $diskName = null);
  public function massDataSave(string $model, array $data);
  public function twoModelsStore(string $model, int $id, string $relation, array $data);
  public function update(string $model, array $data, int $id,  string $imageDir = null, string $tableName = null, string $diskName = null);
  public function delete(string $model, int $id);
}