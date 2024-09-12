<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class OnlyCyrillic implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (!preg_match('/[а-яА-ЯёЁ -]/u', $value)) {
            $fail("Для поля $attribute разрешено использование только кириллицы, пробела и тире.");
        }
    }
}
