<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['slug', 'image'];

    public function translations()
    {
        return $this->hasMany(BlogTranslation::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_category');
    }

    public function translation($locale)
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
