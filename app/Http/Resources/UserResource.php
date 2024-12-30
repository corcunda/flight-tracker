<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => Hashids::encode($this->id), // Encoded ID
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => ($this->created_at) ? $this->created_at->toDateTimeString() : null,
            'updated_at' => ($this->created_at) ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
