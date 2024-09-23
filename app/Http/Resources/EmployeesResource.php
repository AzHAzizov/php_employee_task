<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (empty($this->id)) {
            return [];
        }
    
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'position' => $this->position,
            'email' => $this->email,
            'phone_home' => $this->phone_home,
            'notes' => $this->notes,
            // Теперь проверяем наличие `supervisor` и `subordinates` вручную
            'supervisor' => isset($this->supervisor) ? new EmployeesResource($this->supervisor) : null,
            'subordinates' => isset($this->subordinates) ? EmployeesResource::collection($this->subordinates) : [],
        ];
    }
}
