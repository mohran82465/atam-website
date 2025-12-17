<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
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
            'image' => $this->image,
            'translations' => $this->translations->mapWithKeys(fn ($t) => [
                $t->locale => [
                    'name' => $t->name,
                    'title' => $t->title,
                ]
            ]),
        ];
    }
}
