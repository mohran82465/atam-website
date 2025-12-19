<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceShowResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->image,
            'icon' => $this->icon,
            'translations' => $this->translations->mapWithKeys(fn ($t) => [
                $t->locale => [
                    'title' => $t->title,
                    'description' => $t->long_description,
                    'features' => $t->features,
                ]
            ]),

        ];
    }
}
