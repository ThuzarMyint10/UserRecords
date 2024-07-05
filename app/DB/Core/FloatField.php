<?php

namespace App\DB\Core;

use App\Exceptions\CrudException;

class FloatField extends Field
{
    public function execute()
    {
        return $this->value;
    }
}
