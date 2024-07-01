<?php

namespace App\DB\Core;

use App\Db\Core\Crud;
use App\Exceptions\CrudException;
use Illuminate\Support\Facades\Config;

class ImageField extends Field
{
  public $tableName, $imageDirectory, $diskName;

  public function __construct()
  {
    $this->tableName = Crud::$tableName;
    $this->imageDirectory = Crud::$imageDirectory;
    $this->diskName = Crud::$diskName;
  }

  public function execute()
  {
    if (!$this->value) {
      throw CrudException::emptyData();
    }

    if ($this->tableName === Config::get('variables.IMAGE_MODEL')) {
      $uploadedFile = $this->value;
      $imageName = round(microtime(true) * 1000)  . '.' . $uploadedFile->extension();
      $finalImagePath = $this->imageDirectory . $imageName;
      Crud::storeImage($uploadedFile, $this->imageDirectory, $imageName, $this->diskName);
      return $this->value = $finalImagePath;
    }
  }
}
