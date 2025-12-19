<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

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
            'image' => $this->image ? env('APP_URL') . '/'. $this->image : null,
            'translations' => $this->translations->mapWithKeys(fn($t) => [
                $t->locale => [
                    'name' => $t->name,
                    'title' => $t->title,
                ]
            ]),
        ];
    }
}
