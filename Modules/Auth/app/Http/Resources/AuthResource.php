<?php

namespace Modules\Auth\App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user' => [
                'user_id' => $this->user_id,
                'name' => $this->name,
                'email' => $this->email
            ],
        ];
    }
}