<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;
    protected $fillable = ['image'];

    public function translations()
    {
        return $this->hasMany(TeamMemberTranslation::class);
    }

    public function translation($locale)
    {
        return $this->translations()->where('locale', $locale)->first();
    }
}
