<?php

namespace App\Http\Resources\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'token' => $this->resource['token'],
                'user' => $this->resource['user']->only('id', 'name', 'email')
            ],
            'message' => $this->resource['message']
        ];
    }
}
