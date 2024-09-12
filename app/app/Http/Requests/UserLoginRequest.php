<?php

namespace App\Http\Requests;

use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam('phone', 'string', 'Номер телефона пользователя', required: true, example: '89997008885')]
#[BodyParam('code', 'string', 'Код подтверждения', required: false, example: '8885')]
class UserLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', new PhoneRule()],
            'code' => ['numeric', 'digits:4'],
        ];
    }
}
