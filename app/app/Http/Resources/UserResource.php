<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    =>  $this->id,
            'phone'  =>  $this->phone,
            'lastName'  =>  $this->last_name,
            'name'  =>  $this->name,
            'middleName'  =>  $this->middle_name,
            'email'  =>  $this->email,
            'deleted'   =>  !is_null($this->deleted_at),
        ];
    }
}
