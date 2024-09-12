<?php

declare(strict_types=1);

namespace App\Constants;

class AuthConstants
{
    public const PHONE_VERIFY_CODE_LIFETIME = 5;

    public const ENV_PROD = 'production';

    public const TOKEN_LIFETIME = 1440;

    public const TOKEN_TTL = 604800;

    public const CONFIRM_TYPE = 'confirm';

    public const REGISTER_TYPE = 'register';

    public const AUTH_TYPE = 'auth';

    public const API_GUARD = 'api';
}
