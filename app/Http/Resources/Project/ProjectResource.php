<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'slug' => $this->slug,
            'image' => $this->image ? env('APP_URL') . '/'. $this->image : null,
            'translations' => $this->translations->mapWithKeys(fn ($t) => [
                $t->locale => [
                    'name' => $t->name,
                    'body' => $t->body,
                    'problem' => $t->problem,
                    'solve' => $t->solve,
                    'tech' => $t->tech,
                ]
            ]),
        ];
    }
}
