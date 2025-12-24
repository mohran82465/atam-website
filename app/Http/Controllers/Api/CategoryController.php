<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryResource;
use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // private function formatTranslations($translations)
    // {
    //     return $translations->mapWithKeys(function ($t) {
    //         return [
    //             $t->locale => [
    //                 'name' => $t->name,
    //             ]
    //         ];
    //     });
    // }

    // public function index(Request $request)
    // {
    //     $categories = Category::with('translations')->get();

    //     // $data = $categories->map(function ($category) {
    //     //     return [
    //     //         'id' => $category->id,
    //     //         'slug' => $category->slug,
    //     //         'translations' => $this->formatTranslations($category->translations),
    //     //     ];
    //     // });

    //     // return response()->json($data, 200);
    //     return CategoryResource::collection($categories); 
    // }

    // public function show($slug)
    // {
    //     $category = Category::with('translations')
    //         ->where('slug', $slug)
    //         ->firstOrFail();

    //     // return response()->json([
    //     //     'id' => $category->id,
    //     //     'slug' => $category->slug,
    //     //     'translations' => $this->formatTranslations($category->translations),
    //     // ], 200);
    //     return new CategoryResource($category); 
    // }
    
    // }
    public function indexBlog(){
        $categories = BlogCategory::with('translations')->get(); 
        return CategoryResource::collection($categories); 
    }

    public function indexProject(){
        $categories = ProjectCategory::with('translations')->get(); 
        return CategoryResource::collection($categories); 
    }

}
