<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\DB\Core\JsonField;
use App\DB\Core\StringField;
use App\Exceptions\CrudException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function saveableFields($column): object
    {
        $arr = [
            'uuid' => StringField::new(),
            'gender' => StringField::new(),
            'name' => JsonField::new(),
            'location' => JsonField::new(),
            'age' => StringField::new(),
        ];
        if (!array_key_exists($column, $arr)) {
            throw CrudException::missingAttributeException();
        }

        return  $arr[$column];
    }
}
