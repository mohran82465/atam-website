<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCategoryTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['project_category_id', 'locale', 'name'];

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class);
    }
}
