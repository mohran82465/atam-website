<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreativeDepartments extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'image',
    ];

    public function translations() {
        return $this->hasMany(CreativeDepartmentsTranslations::class, 'creative_departments_id'); 
    }
    
    // Fixed typo: 'local' -> 'locale' and 'traslation' -> 'translation'
    public function translation($locale) {
        return $this->translations()->where('locale', $locale)->first();
    }
}
