<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_completed' => $this->is_completed,
            'title' => $this->title,
            'description' => $this->description,
            'deadline_date' => ($this->deadline_date) ? $this->deadline_date->format('Y-m-d') : null,
            'completion_date' => ($this->completion_date) ? $this->completion_date->format('Y-m-d') : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
