<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreativeDepartmentsTranslations extends Model
{
    use HasFactory;
    protected $fillable = [
        'creative_departments_id',
        'locale',
        'name',
        'description',
    ];

    public function creativeDepartments() {
        return $this->belongsTo(CreativeDepartments::class, 'creative_departments_id'); 
    }
}
