<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChunkTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'chunk_id',
        'locale',
        'title',
        'body',
    ];

    public function chunk()
    {
        return $this->belongsTo(Chunk::class);
    }
}
