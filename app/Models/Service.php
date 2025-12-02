<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['name','image','slug','icon'];

    public function translations(){
        return $this->hasMany(ServiceTranslation::class);
    }
    public function translation($locale){
        return $this->translations()->where('locale',$locale)->first();
    }
}
