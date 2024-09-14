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


        if(empty($this->id)) {
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
            'supervisor_id' => $this->supervisor_id,
            'supervisor' => new EmployeesResource($this->whenLoaded('supervisor')),
            'subordinates' => EmployeesResource::collection($this->whenLoaded('subordinates')),
        ];
    }
}
