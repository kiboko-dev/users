<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes\ResponseField;

class TokenResource extends JsonResource
{
    #[ResponseField("id", "string", example: 'qweqwe')]
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this['access_token'],
            'refresh_token' => $this['refresh_token'],
            'expires_in' => $this['expires_in']
        ];
    }
}
