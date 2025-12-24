<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
    use HasFactory;
    protected $fillable = ['slug','name'];

    public function translations()
    {
        return $this->hasMany(ProjectCategoryTranslation::class);
    }

    public function translation($locale)
    {
        return $this->translations()->where('locale', $locale)->first();
    }
    public function projects(){
        return $this->belongsToMany(Project::class, 'category_project');
    }
}
