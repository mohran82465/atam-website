<?php

namespace App\Http\Resources\Chunk;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChunkResource extends JsonResource
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
            'page' => $this->page,
            'slug' => $this->slug,
            // 'thumbnail' => $this->thumbnail ?: null,
            'thumbnail' => $this->thumbnail ? env('APP_URL') . '/'. $this->thumbnail : null,

            'translations' => $this->translations->mapWithKeys(fn($t) => [
                $t->locale => [
                    'title' => $t->title,
                    'body' => $t->body,
                ]
            ]),
        ];
    }
}
