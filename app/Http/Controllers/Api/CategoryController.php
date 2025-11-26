<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private function formatTranslations($translations)
    {
        return $translations->mapWithKeys(function ($t) {
            return [
                $t->locale => [
                    'name' => $t->name,
                ]
            ];
        });
    }

    public function index(Request $request)
    {
        $categories = Category::with('translations')->get();

        $data = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'slug' => $category->slug,
                'translations' => $this->formatTranslations($category->translations),
            ];
        });

        return response()->json($data, 200);
    }

    public function show($slug)
    {
        $category = Category::with('translations')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'id' => $category->id,
            'slug' => $category->slug,
            'translations' => $this->formatTranslations($category->translations),
        ], 200);
    }


}
