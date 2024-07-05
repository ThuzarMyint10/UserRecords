<?php

namespace App\DB\Core;

use App\Exceptions\CrudException;

class JsonField extends Field
{
    public function execute()
    {
        return $this->value;
    }
}
