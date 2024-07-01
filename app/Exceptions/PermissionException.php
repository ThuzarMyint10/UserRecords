<?php

namespace App\Exceptions;

use Exception;

class PermissionException extends Exception
{
    public static function foribden(): static
    {
        return new static('You do not have the required authorization.', 403);
    }
}
