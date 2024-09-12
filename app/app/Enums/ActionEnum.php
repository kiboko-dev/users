<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ActionEnum extends Enum
{
    public const CREATE = 'create';

    public const DELETE = 'delete';

    public const DESTROY = 'destroy';

    public const RESTORE = 'restore';

    public const UPDATE = 'update';

    public static function getDefault(): string
    {
        return self::CREATE;
    }
}
