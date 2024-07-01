<?php

namespace App\Db\Core;

use App\Exceptions\CrudException;
use App\Exceptions\CustomException;
use App\ValueObjects\FullName;
use Exception;
use illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class Crud
{
    public function __construct(
        private Model $model,
        private ?array $data = null,
        private ?int $id = null,
        private ?string $relation = null,
        private bool $storeMode = false,
        private bool $twoModelsStoreMode = false,
        private bool $editMode = false,
        private bool $deleteMode = false,
    ) {
        self::$tableName = $model->getTable();
    }

    public static string $imageDirectory = '';
    public static string $tableName = '';
    public static string $diskName = '';
    private ?Model $record = null;

    public function setImageDirectory(string $directoryPath, string $tablename, string $diskName)
    {
        self::$imageDirectory = $directoryPath;
        self::$tableName = $tablename;
        self::$diskName = $diskName;
    }

    public function execute(): mixed
    {
        try {
            if ($this->editMode) {
                return $this->handleEditMode();
            } elseif ($this->deleteMode) {
                return $this->handleDeleteMode();
            } elseif ($this->storeMode) {
                return $this->handleStoreMode();
            } else {
                return $this->handleTwoModelsStoreMode();
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    protected function iterateData(array $data, ?Model $record = null): Model
    {
        $target = $record ?? $this->model;
        foreach ($data as $column => $value) { //['full_name'] = {first_name:"mg",last_name:"mg"}
            if (!is_object($value) || $column === 'upload_url') {
                $target->{$column} = $this->savableField($column)->setValue($value)->execute();
            } else {
                $target->$column = $value; //$user->full_name = {first_name:"mg",last_name:"mg"} (call the fullName() locates in the user model)
            }
        }
        return $target;
    }

    protected function handleStoreMode(): Model|bool
    {
        $model = $this->iterateData($this->data, null);
        $model = $model->save() ? $this->model : false;
        if (!$model->wasRecentlyCreated) {
            throw CrudException::internalServerError();
        }
        return $model;
    }

    protected function handleTwoModelsStoreMode(): void
    {
        $instance = $this->model->findOrFail($this->id);
        $relationName = $this->relation;
        if (!method_exists($instance, $relationName)) {
            throw CrudException::methodNotFound();
        }
        $instance->$relationName()->attach($this->data);
    }

    protected function handleEditMode(): Model|bool
    {
        $this->record = $this->model->find($this->id);
        if (!$this->record) {
            throw CustomException::notFound();
        }
        if ($this->record->upload_url) {
            if ($this->model->getTable() === Config::get('variables.IMAGE_MODEL')) {
                $this->deleteImage();
            }
        }

        $record = $this->iterateData($this->data, $this->record);
        $record = $record->save() ? $this->record : false;
        if (!$record) {
            throw CrudException::internalServerError();
        }
        return $record;
    }

    protected function handleDeleteMode(): bool
    {
        $this->record = $this->model->find($this->id);
        if (!$this->record) {
            throw CustomException::notFound();
        }
        if ($this->record->upload_url) {
            if ($this->model->getTable() === Config::get('variables.IMAGE_MODEL')) {
                $this->deleteImage();
            }
        }
        $success = $this->record->delete() ? true : false;
        if (!$success) {
            throw CustomException::internalServerError();
        }
        return $success;
    }

    public function savableField($column): object
    {
        return $this->model->saveableFields($column);
    }

    public function deleteImage(): bool
    {
        $old_image = $this->record->upload_url;
        return $old_image ? Storage::disk(self::$diskName)->delete($old_image) : false;
    }

    public static function storeImage($value, $imageDirectory, $imageName, $diskName)
    {
        $value->storeAs($imageDirectory, $imageName, $diskName);
    }
}
