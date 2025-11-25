<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'locale',
        'name',
        'body',
        'problem',
        'solve',
        'tech',
    ];

    protected $casts = [
        'tech' => 'array',
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
