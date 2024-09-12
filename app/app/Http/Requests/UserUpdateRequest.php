<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam(name: 'lastName', type: 'string', description: 'Фамилия пользователя', required: false, example: 'Иванов')]
#[BodyParam(name: 'name', type: 'string', description: 'Имя пользователя', required: false, example: 'Иван')]
#[BodyParam(name: 'middleName', type: 'string', description: 'Отчество пользователя', required: false, example: 'Иванович')]
#[BodyParam(name: 'email', type: 'string', description: 'E-mail пользователя', required: false, example: 'ivanov@yandex.eu')]
class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lastName' => 'string',
            'name' => 'string',
            'middleName' => 'string',
            'email' => 'email:rfc,dns',
        ];
    }
}
