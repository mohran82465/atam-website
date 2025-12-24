<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // public function index(Request $request)
    // {
        // $locale = $request->query('lang', app()->getLocale());

        // $projects = Project::with('translations')->get()->map(function ($project) use ($locale) {

        //     $translations = $project->translations->mapWithKeys(function ($t) {
        //         return [
        //             $t->locale => [
        //                 'name' => $t->name,
        //                 'body' => $t->body,
        //                 'problem' => $t->problem,
        //                 'solve' => $t->solve,
        //                 'tech' => $t->tech,
        //             ]
        //         ];
        //     });
        //     return
        //         [
        //             'id' => $project->id,
        //             'slug' => $project->slug,
        //             'image' => $project->image ? $project->image : null,
        //             'translations' => $translations,
        //         ];
        // });

        // return response()->json($projects, 200);
    //     $projects = Project::with('translations')->paginate();
    //     return ProjectResource::collection($projects); 
    // }

 public function index(Request $request)
{
    $search     = $request->query('search');      // text search
    $categoryId = $request->query('category_id'); // filter by category

    $projects = Project::query()
        ->where('status', true) 
        ->with([
            'translations',
            'categories.translations',
        ])

        ->when($search, function ($query) use ($search) {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        })

        ->when($categoryId, function ($query) use ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('project_categories.id', $categoryId);
            });
        })

        ->latest()
        ->paginate(10);

    return ProjectResource::collection($projects);
}


    public function show(Request $request, $slug)
    {
        $project = Project::with('translations')
            ->where('slug', $slug)
            ->firstOrFail();

        // $translations = $project->translations->mapWithKeys(function ($t) {
        //     return [
        //         $t->locale => [
        //             'name' => $t->name,
        //             'body' => $t->body,
        //             'problem' => $t->problem,
        //             'solve' => $t->solve,
        //             'tech' => $t->tech,
        //         ]
        //     ];
        // });

        // return response()->json([
        //     'id' => $project->id,
        //     'slug' => $project->slug,
        //     'image' => $project->image ? asset('storage/' . $project->image) : null,
        //     'translations' => $translations,
        // ], 200);
        return new ProjectResource($project); 
    }

}
