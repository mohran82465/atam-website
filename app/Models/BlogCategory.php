<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;
    protected $fillable = ['slug','name'];

    public function translations()
    {
        return $this->hasMany(BlogCategoryTranslation::class);
    }

    public function translation($locale)
    {
        return $this->translations()->where('locale', $locale)->first();
    }
    public function blogs(){
        return $this->belongsToMany(Blog::class, 'category_blog');
    }
}
