<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam(name: 'phone', type: 'string', description: 'Номер телефона пользователя', required: true, example: '89997000000')]
#[BodyParam(name: 'lastName', type: 'string', description: 'Фамилия пользователя', required: true, example: 'Иванов')]
#[BodyParam(name: 'name', type: 'string', description: 'Имя пользователя', required: true, example: 'Иван')]
#[BodyParam(name: 'middleName', type: 'string', description: 'Отчество пользователя', required: true, example: 'Иванович')]
#[BodyParam(name: 'email', type: 'string', description: 'E-mail пользователя', required: true, example: 'ivanov@yandex.eu')]
class UserRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required','string', 'unique:users,phone'],
            'lastName' => ['required', 'string'],
            'name' => ['required', 'string'],
            'middleName' => ['required', 'string'],
            'email' => ['required', 'string', 'email:rfc,dns'],
        ];
    }
}
