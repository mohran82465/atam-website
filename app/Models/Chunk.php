<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chunk extends Model
{
    use HasFactory;
    protected $fillable = [
        'page',
        'slug',
        'thumbnail',
    ];

    protected $with = ['translations'];

    public function translations()
    {
        return $this->hasMany(ChunkTranslation::class);
    }
    public function translation($locale)
    {
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', app()->getLocale());
    }
}
