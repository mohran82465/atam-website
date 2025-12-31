<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['slug', 'image','url','status'];

    public function translations()
    {
        return $this->hasMany(ProjectTranslation::class);
    }

    public function translation($locale)
    {
        return $this->translations()->where('locale', $locale)->first();
    }
    public function categories()
    {
        return $this->belongsToMany(ProjectCategory::class, 'category_project');
    }

}
