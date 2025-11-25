<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->query('lang', app()->getLocale());

        $projects = Project::with('translations')->get()->map(function ($project) use ($locale) {

            $translations = $project->translations->mapWithKeys(function ($t) {
                return [
                    $t->locale => [
                        'name' => $t->name,
                        'body' => $t->body,
                        'problem' => $t->problem,
                        'solve' => $t->solve,
                        'tech' => $t->tech,
                    ]
                ];
            });
            return
                [
                    'id' => $project->id,
                    'slug' => $project->slug,
                    'image' => $project->image ? $project->image : null,
                    'translations' => $translations,
                ];
        });

        return response()->json($projects, 200);
    }

    public function show(Request $request, $slug)
    {
        $project = Project::with('translations')
            ->where('slug', $slug)
            ->firstOrFail();

        $translations = $project->translations->mapWithKeys(function ($t) {
            return [
                $t->locale => [
                    'name' => $t->name,
                    'body' => $t->body,
                    'problem' => $t->problem,
                    'solve' => $t->solve,
                    'tech' => $t->tech,
                ]
            ];
        });

        return response()->json([
            'id' => $project->id,
            'slug' => $project->slug,
            'image' => $project->image ? asset('storage/' . $project->image) : null,
            'translations' => $translations,
        ], 200);
    }

}
