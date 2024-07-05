<?php

namespace App\Models;

use App\DB\Core\FloatField;
use App\DB\Core\IntegerField;
use App\DB\Core\DatetimeField;
use App\Exceptions\CrudException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyRecord extends Model
{
    use HasFactory;
    public function saveableFields($column): object
    {
        $arr = [
            'date' => DatetimeField::new(),
            'male_count' => IntegerField::new(),
            'female_count' => IntegerField::new(),
            'male_avg_age' => FloatField::new(),
            'female_avg_age' => FloatField::new(),
        ];
        if (!array_key_exists($column, $arr)) {
            throw CrudException::missingAttributeException();
        }

        return  $arr[$column];
    }
}
