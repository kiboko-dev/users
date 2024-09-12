<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class PhoneRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (!preg_match('/^(?:\+7|7|8)+\d{10}$/', $value)) {
            $fail('Некорректный формат номера телефона');
        }
    }
}
