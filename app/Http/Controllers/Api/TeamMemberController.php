<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->query('lang', app()->getLocale());
        $teams = TeamMember::with('translations')->get()->map(function ($team) use ($locale) {
            $translations = $team->translations->mapWithKeys(function($t){
                return [
                    $t->locale => [
                            'name'=>$t->name, 
                        'title'=>$t->title, 
                    ]
                    ];
            });



            return [
                'id' => $team->id,
                'image' => $team->image,
                'translations' => $translations
            ];
        });

        return response()->json($teams, 200);
    }
}
