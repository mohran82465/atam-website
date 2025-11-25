<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMemberTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'team_member_id',
        'locale',
        'name',
        'title',
    ];

    public function teamMember()
    {
        return $this->belongsTo(TeamMember::class);
    }
}
