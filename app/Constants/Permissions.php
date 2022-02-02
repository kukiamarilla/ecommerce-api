<?php

namespace App\Constants;

use MyCLabs\Enum\Enum;

class Permissions extends Enum
{
    public const VIEW_USERS = 'view_users';

    public static function all(): array
    {
        return collect(static::toArray())->values()->toArray();
    }

}
