<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam(
    name: 'ids',
    type: 'array',
    description: 'Массив из ID пользователей',
    required: true,
    example: ['5db01bb1-5667-4364-a1ec-a3ea28083522', 'de2b9e99-3995-41a1-b32a-069efd77dbc1']
)]
class UserRestoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'integer', 'exists:histories,model_id'],
        ];
    }
}
