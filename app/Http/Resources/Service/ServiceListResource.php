<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceListResource extends JsonResource
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
            'icon' => $this->icon,
            'image' => $this->image ? env('APP_URL') . '/'. $this->image : null,
            'translations' => $this->translations->mapWithKeys(fn ($t) => [
                $t->locale => [
                    'title' => $t->title,
                    'short_description' => $t->short_description,
                    'long_description'  => $t->long_description,
                    'features' => $t->features,
                ]
            ]),
        ];
    }
}
